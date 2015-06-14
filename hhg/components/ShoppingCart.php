<?php
namespace hhg\components;
use Yii;
use hhg\models\Cart;
/**
* shoppint cart class
* @author hackerone
*/
class ShoppingCart extends yii\base\Component{

    public $product_list = array();
    //$product_list = array(
    //      "1" => array(
    //          'quantity' => '2',
    //      ),
    //      '3' => array(
    //          'quantity' => '3',
    //      ),
    //
    //);

    public function init(){
        //read from cookie
        parent::init();
    }
    /**
     * add product into shopping cart 
     * if user is login the info saved into mysql
     * if user is not login the info save into cookie
     * @param intval $uid        user's id  if user not login null
     * @param intval $goods_id product id
     * @param intval $quantity   product number
     */
    public function addToCart($uid,$goods_id,$quantity,$storeroom_id){
        return $this->updateCartForUser($uid,$goods_id,$quantity,$storeroom_id);
    }
    /**
     * update for user shopping cart
     * this cart is saved into database
     * @param  [type] $uid        [description]
     * @param  [type] $goods_id [description]
     * @param  [type] $quantity   [description]
     * @return [type]             [description]
     */
    public function updateCartForUser($uid,$material_id,$quantity,$storeroom_id){
        $user_cart = Cart::getCartProductIdsByUid($uid,$material_id,$storeroom_id);
        if(empty($user_cart)){
            $cart = new Cart;
            $cart->uid = $uid;
            $cart->material_id = $material_id;
            $cart->storeroom_id = $storeroom_id;
            $cart->quantity = $quantity;
            if($cart->save()){
                return true;
            }
        }else{
            $user_cart->quantity = $user_cart->quantity + $quantity;
            if($user_cart->save()){
                return true;
            }
        }
        return false;
    }
    /**
     * delete a product from cart cookie
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function deleteProductFromCart($id){
        return Cart::deleteAll(['id'=>$id]);
    }
}