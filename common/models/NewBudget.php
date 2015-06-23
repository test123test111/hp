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
     * table new_budget_total and new_budget relationship
     * @return [type] [description]
     */
    public function getTotal(){
        return $this->hasOne(NewBudgetTotal::className(),['budget_id'=>'id']);
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
        $query = static::find()->with(['owner','total'])->orderBy(['id'=>SORT_DESC]);

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
     * get all owner who have budget 
     * @param  [type] $params [description]
     * @return [type]         [description]
     */
    public static function getExportData($params){
        $query = static::find()->with(['owner','total'])->orderBy(['id'=>SORT_DESC]);

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
        $str = "序号,预算所有人,库房位置,月份/季度,预算金额,变动,已使用金额,剩余金额,使用百分比,备注\n";
        $offset = 0;
        $limit = 100;
        $data = [];
        $i = 1;
        while(true){
            $results = $query->limit($limit)->offset($offset)->all();
            if(empty($results)){
                break;
            }
            foreach($results as $key =>$result){
                $data[$i]['id'] = $i;
                $adjust = NewBudgetAdjust::find()->select('price')->where(['budget_id'=>$result->id])->sum('price');
                $consume = NewBudgetConsume::find()->select('price')->where(['budget_id'=>$result->id])->sum('price');
                $data[$i]['name'] = $result->owner->english_name;
                $data[$i]['storeroom'] = $result->storeroom->name;
                $data[$i]['time'] = $result->time;
                $data[$i]['price'] = $result->price;
                $data[$i]['change'] = $adjust;
                $data[$i]['used'] = $consume;
                $data[$i]['canuse'] = $result->price + $adjust - $consume;
                $data[$i]['usepercent'] = round(($consume / ($result->price + $adjust))) * 100;
                $data[$i]['info'] = "";
                $str .= $data[$i]['id'].",".$data[$i]['name'].",".$data[$i]['storeroom'].",".$data[$i]['time'].",".$data[$i]['price'].",".$data[$i]['change'].",".$data[$i]['used'].",".$data[$i]['canuse'].",".$data[$i]['usepercent'].",".$data[$i]['info']."\r\n"; //用引文逗号分开
                $i++;
            }
           
            $offset += $limit;
            if ($offset > $count) {
                break;
            }
        }
        return $str;
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
    /**
     * get budget total and consume 
     * @param  [type] $uid          [description]
     * @param  [type] $storeroom_id [description]
     * @return [type]               [description]
     */
    public static function getPriceTotalAndConsume($uid,$storeroom_id){
        $storeroom = Storeroom::findOne($storeroom_id);
        $year = date('Y');
        if(!empty($storeroom)){
            if($storeroom->level == Storeroom::STOREROOM_LEVEL_IS_CENTER){
                $time = ceil((date('n',strtotime($date)))/3);
            }else{
                $time = date('m');
            }

            $budget = static::find()->where(['owner_id'=>$uid,'storeroom_id'=>$storeroom_id,'year'=>$year,'time'=>$time])->one();
            $adjust = NewBudgetAdjust::find()->select('price')->where(['budget_id'=>$budget->id])->sum('price');

            if(!empty($budget)){
                $total = NewBudgetTotal::find()->where(['budget_id'=>$budget->id])->one();
                return [($budget->price + $adjust),($budget->price + $adjust - $total->price)];
            }
        }
        return 0;
    }
    /**
     * [getCurrentIdByUid description]
     * @param  [type] $uid [description]
     * @return [type]      [description]
     */
    public static function getCurrentIdByUid($uid,$storeroom_id){
        $storeroom = Storeroom::findOne($storeroom_id);
        $year = date('Y');
        if(!empty($storeroom)){
            if($storeroom->level == Storeroom::STOREROOM_LEVEL_IS_CENTER){
                $time = ceil((date('n',strtotime($date)))/3);
            }else{
                $time = date('m');
            }

            $budget = static::find()->where(['owner_id'=>$uid,'storeroom_id'=>$storeroom_id,'year'=>$year,'time'=>$time])->one();
            if(!empty($budget)){
                return $budget->id;
            }
            return 0;
        }
        return 0;
    }
    /**
     * [getCanUseStorerooms description]
     * @return [type] [description]
     */
    public function getCanUseStorerooms(){
        $rs = Storeroom::find()->all();
        $arr = [];
        if($rs){
            foreach($rs as $key=>$v){
                $arr[$v['id']]=$v['name'];
            }

        }
        return $arr;
    }
}
