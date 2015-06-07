<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class Budget extends ActiveRecord
{
	public function behaviors()
    {
        return yii\helpers\BaseArrayHelper::merge(
            parent::behaviors(),
            [
                'timestamp' => [
                    'class' => 'yii\behaviors\TimestampBehavior',
                    'attributes' => [
                        ActiveRecord::EVENT_BEFORE_INSERT => ['created', 'modified'],
                        ActiveRecord::EVENT_BEFORE_UPDATE => 'modified',
                    ],
                    'value' => function (){ return date("Y-m-d H:i:s");}
                ],
                'attributeStamp' => [
                      'class' => 'yii\behaviors\AttributeBehavior',
                      'attributes' => [
                          ActiveRecord::EVENT_BEFORE_INSERT => ['created_uid'],
                      ],
                      'value' => function () {
                          return Yii::$app->user->id;
                      },
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
        return 'budget';
    }
    /**
     * [updateOwnerBudget description]
     * @param  [type] $owner_id [description]
     * @param  [type] $price    [description]
     * @return [type]           [description]
     */
    public static function updateOwnerBudget($owner_id,$category_id,$price,$created_type = '0'){
        $db = static::getDb();
        $transaction = $db->beginTransaction();
        try{
    		$model = new static;
        $model->owner_id = $owner_id;
    		$model->category = $category_id;
    		$model->price = $price;
    		$model->created_type = $created_type;
    		$model->save();

    		$budgetTotal = BudgetTotal::find()->where(['owner_id'=>$owner_id])->one();
    		if(empty($budgetTotal)){
    			$budgetTotal = new BudgetTotal;
	    		$budgetTotal->owner_id = $owner_id;
          $budgetTotal->category = $category_id;
	    		$budgetTotal->price = $price;
	    		$budgetTotal->save();
    		}else{
    			$budgetTotal->price += $price;
    			$budgetTotal->update();
    		}
    		
            $transaction->commit();
        }catch (\Exception $e) {
            $transaction->rollback();
            throw new \Exception($e->getMessage(), $e->getCode());
        }
                
    	
    }
}
