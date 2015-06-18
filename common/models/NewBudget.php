<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use backend\models\Owner;
use backend\models\Storeroom;
class NewBudget extends ActiveRecord
{
	public function behaviors()
    {
        return yii\helpers\BaseArrayHelper::merge(
            parent::behaviors(),
            [
                'timestamp' => [
                    'class' => 'yii\behaviors\TimestampBehavior',
                    'attributes' => [
                        ActiveRecord::EVENT_BEFORE_INSERT => ['created'],
                    ],
                    'value' => function (){ return date("Y-m-d H:i:s");}
                ],
           ]
        );
    }
    /**
     * function_description
     *
     *
     * @return
     */
    public static function tableName() {
        return 'new_budget';
    }
    /**
     * function_description
     *
     *
     * @return
     */
    public function rules() {
        return [
            [['owner_id','storeroom_id','price','owner_id','year','time'],'required'],
            ['year','checkOwnerYear'],
        ];
    }
    /**
     * table storeroom and table new_budget relationship
     * @return [type] [description]
     */
    public function getStoreroom(){
        return $this->hasOne(Storeroom::className(),['id'=>'storeroom_id']);
    }
    /**
     * table owner and table new_budget relationship
     * @return [type] [description]
     */
    public function getOwner(){
        return $this->hasOne(Owner::className(),['id'=>'owner_id']);
    }
    /**
     * table new_budget and new_budget_adjust relationship
     * @return [type] [description]
     */
    public function getAdjusts(){
        return $this->hasMany(NewBudgetAdjust::className(),['budget_id'=>'id'])->orderBy(['id'=>SORT_DESC]);
    }
    /**
     * validate owner one year one time can only have a record 
     * @return [type] [description]
     */
    public function checkOwnerYear(){
        if($this->isNewRecord){
            $result = static::find()->where(['owner_id'=>$this->owner_id,'year'=>$this->year,'time'=>$this->time])->one();
            if($result){
                  $this->addError('year', '本季度/月份该所有人已经存在预算,请勿重复添加');
            }
        }
    }
    /**
     * get all owner who have budget 
     * @param  [type] $params [description]
     * @return [type]         [description]
     */
    public static function getAllOwnerBudget($params){
        $query = static::find()->with(['owner'])->orderBy(['id'=>SORT_DESC]);

        if(isset($params['owner_id']) && $params['owner_id'] != ""){
            $query->andWhere(['owner_id'=>$params['owner_id']]);
        }
        if(isset($params['storeroom_id']) && $params['storeroom_id'] != ""){
            $query->andWhere(['storeroom_id'=>$params['storeroom_id']]);
        }
        if(isset($params['year']) && $params['year'] != ""){
            $query->andWhere(['year'=>$params['year']]);
        }
        if(isset($params['time']) && $params['time'] != ""){
            $query->andWhere(['time'=>$params['time']]);
        }
        
        $count = $query->count();
        $pages = new \yii\data\Pagination(['totalCount' => $count]);
        $ret = [];
        $query->offset($pages->offset)->limit(20);

        $data = $query->all();
        return [$data,$pages,$count];
    }
    /**
     * update budget adjust 
     * @param  price + - 
     * @return [type]        [description]
     */
    public function updateAdjust($price,$operate_uid){
        $model = new NewBudgetAdjust;
        $model->budget_id = $this->id;
        $model->price = $price;
        $model->created_uid = $operate_uid;
        if($model->save(false)){
            //update budget total 
            
            $budgetTotal = NewBudgetTotal::find()->where(['budget_id'=>$this->id])->one();
            if(!empty($budgetTotal)){
                $budgetTotal->updateCounters(['price'=>$price]);
            }
        }
        return true;
    }
    /**
     * create budget Total
     * @return [type] [description]
     */
    public function createBudgetTotal(){
        $model = new NewBudgetTotal;
        $model->budget_id = $this->id;
        $model->price = $this->price;
        if($model->save()){
            return true;
        }
        return false;
    }
}
