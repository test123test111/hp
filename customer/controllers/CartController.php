<?php

namespace customer\controllers;

use Yii;
use customer\models\Cart;
use customer\models\Stock;
use customer\models\Material;
use customer\components\CustomerController;
use backend\models\Order;
use customer\models\Address;
use customer\models\Storeroom;
use customer\models\Owner;
use common\models\NewBudget;
class CartController extends CustomerController {
    public $layout = false;
    public $enableCsrfValidation;
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['add', 'check','index','delete','batchadd','addressinfo'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
    /**
     * action for add cart
     * @return [type] [description]
     */
    public function actionAdd(){
		if(Yii::$app->request->isPost){
            $material_id = Yii::$app->request->post('material_id');
            $storeroom_id = Yii::$app->request->post('storeroom_id');
            $quantity = Yii::$app->request->post('quantity');

            $material = Material::findOne($material_id);
            // if(empty($material) || $material->owner_id != $uid){
            // 	echo json_encode(['data'=>'','err'=>'物主身份不符合','errno'=>10000]);
            // 	Yii::$app->end();
            // }
            $total = Stock::getStockByUidAndStorageIdAndMaterialId($storeroom_id,$material_id);
            if($total == 0){
            	echo json_encode(['data'=>'','err'=>'所选物料库存不够','errno'=>10001]);
            	Yii::$app->end();
            }
            $data = Yii::$app->shoppingCart->addToCart(Yii::$app->user->id,$material_id,$quantity,$storeroom_id);
            if($data){
            	echo json_encode(['data'=>'','err'=>'添加购物车成功','errno'=>0]);
            	Yii::$app->end();
            }
            echo json_encode(['data'=>'','err'=>'系统错误稍后再试','errno'=>10002]);
        	Yii::$app->end();
        }
    }
    public function actionBatchadd(){
        if(Yii::$app->request->isPost){
            $results = Yii::$app->request->post('items');
            if(!empty($results)){
                foreach($results as $result){
                    $uid = Yii::$app->user->id;
                    $material_id = $result['material_id'];
                    $storeroom_id = $result['storeroom_id'];
                    $quantity = $result['quantity'];

                    $material = Material::findOne($material_id);
                    // if(empty($material) || $material->owner_id != $uid){
                    //     echo json_encode(['data'=>'','err'=>'物主身份不符合','errno'=>10000]);
                    //     Yii::$app->end();
                    // }
                    // $total = Stock::getStockByUidAndStorageIdAndMaterialId($uid,$storeroom_id,$material_id);
                    // if($total == 0){
                    //     echo json_encode(['data'=>'','err'=>'所选物料库存不够','errno'=>10001]);
                    //     Yii::$app->end();
                    // }
                    Yii::$app->shoppingCart->addToCart($uid,$material_id,$quantity,$storeroom_id);
                    // if($data){
                    //     echo json_encode(['data'=>'','err'=>'添加购物车成功','errno'=>0]);
                    // }else{
                    //     echo json_encode(['data'=>'','err'=>'系统错误稍后再试','errno'=>10002]);
                    //     Yii::$app->end();
                    // }
                    
                }
            }
        }
        echo json_encode(['data'=>'','err'=>'添加购物车成功','errno'=>0]);
    }
    /**
     * [actionAddressinfo description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function actionAddressinfo($id){
        $address = Address::findOne($id);
        echo $this->renderPartial('addresslist', ['addr' => $address]);
    }
    /**
     * check cart goods
     * @return [type] [description]
     */
    public function actionCheck(){
        if (Yii::$app->request->isPost) {
            $items = Yii::$app->request->post('items');
            $result = Cart::getCartsInfo($items);
            $userAddress = Address::getUserAddress(Yii::$app->user->id);
            $company = Address::getUserCompany(Yii::$app->user->id);
            $storerooms = Storeroom::find()->all();
            return $this->render('check', array(
                'results'=>$result,
                // 'address'=>$userAddress,
                'company'=>$company,
                'storerooms'=>$storerooms,
                'createusers'=>Owner::getBudgetUsers(Yii::$app->user->id,true),
                'budget' => new NewBudget,
            )); 
        }
    }
    /**
     * action for cart index
     * @return [type] [description]
     */
    public function actionIndex(){
    	$carts = Cart::getCartsByUid(Yii::$app->user->id);
    	return $this->render('index',['carts'=>$carts]);
    }
    /**
     * action for delete cart
     * @return [type] [description]
     */
    public function actionDelete(){
    	if(Yii::$app->request->isPost){
    		$cart_id = Yii::$app->request->post('id');
    		Yii::$app->shoppingCart->deleteProductFromCart($cart_id);
    		echo 1;
    	}
    }
}