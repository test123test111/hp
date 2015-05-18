<?php
namespace operate\models;
use operate\models\Order;
use common\models\Stock;
use common\models\Buyer;
use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use common\models\StockAmount;
class TradeCart extends ActiveRecord
{

    public static function tableName()
    {
        return "trade_cart";
    }

    /*
     * 取商品信息
     */
    public function getStockInfo(){
        return $this->hasOne(Stock::className(),['id'=>'stock_id']);
    }

    /*
     * 取商品库存
     */
    public function getStockAmount(){
        return $this->hasMany(StockAmount::className(),['stock_id'=>'stock_id'])->sum('amount');
    }

    public function getBuyerInfo(){
        return $this->hasOne(Buyer::className(),['id'=>'buyer_id']);
    }

    /*
     * 取用户购物车商品数量
     */
    public static function getUserCartStocks($user_id){
        $query = Static::find();
        $query->andWhere(['user_id'=>$user_id]);
        $query->andWhere(['>','number','0']);
        $data = $query->select('stock_id')->distinct()->all();
        return $data;
    }
    /*
     * 取购买商品的用户数
     */
    public function getStockBuyUsers(){
        return $this->hasMany(Order::className(),['stock_id'=>'stock_id'])->select('user_id')->distinct(true)->count();
    }

    /*
     * 取将某商品放入购物车的vip用户数
     */
    public function getCartVipUsers(){
        return $this->hasMany(UserVip::className(),['user_id'=>'user_id'])->select('user_id')->distinct(true)->count();
    }

    /*
     * 取购物车中购买某商品的用户ID
     */
    public static function getUserByCartStock($stock_id){
        $query = Static::find();
        $query->andWhere(['stock_id'=>$stock_id]);
        $query->select('user_id');
        $query->distinct();
        $data = $query->all();
        $return = [];
        if(!empty($data)){
            foreach($data as $d){
                $return[] = $d->user_id;
            }
        }
        return $return;
    }

    public function attributeLabels(){
        return [
            'stock_id' => '商品ID',
            'create_time' => '添加时间',
            'name' => '商品名称',
            'priceout' => '商品价格',
            'stock_amount' => '剩余库存',
            'buyer' => '买手名',
            'vipusernum' => '放入购物车的VIP用户数'
        ];
    }
    /*
     * 商品上架状态
     */
    public function getStockOnshelf(){
        return ['0'=>'下架'];
    }
}