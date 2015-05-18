<?php
namespace backend\models;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\helpers\BaseArrayHelper;
class Logistic extends ActiveRecord {
    const EXPRESS_ALREADY_OUTPUT = 11001;
    public static $KUAIDI100_KEY = 'rqfAVPxA9966';
    /**
     * function_description
     *
     *
     * @return
     */
    public static function tableName() {
        return 'logistic';
    }
    /**
     * table logistic and order relationship
     * @return [type] [description]
     */
    public function getOrders(){
        return $this->hasOne(Order::className(),['id'=>'order_id']);
    }
    /**
     * table logistic and table logistic_tracking relationship
     * @return [type] [description]
     */
    public function getTracking(){
        return $this->hasMany(LogisticTracking::className(),['logistic_no'=>'logistic_no'])->orderBy(['id'=>SORT_DESC]);
    }
    /**
     * table logistic and table box_order relationship
     * box_order's status is 1
     * @return [type] [description]
     */
    public function getOrderSend(){
        return $this->hasOne(BoxOrder::className(),['order_id'=>'order_id'])->where(['status'=>BoxOrder::ORDER_IS_TO_USER]);
    }
    /**
     * save a new record in logistic table
     * @param  [type] $order_id          [description]
     * @param  [type] $express_no        [description]
     * @param  [type] $logistic_provider [description]
     * @return [type]                    [description]
     */
    public static function saveLogistic($order_id,$express_no,$logistic_provider,$box_id){
        $model = new Static;

        $model->order_id = $order_id;
        $model->logistic_no = $express_no;
        $model->logistic_provider = $logistic_provider;
        $logistic_provider_fixed = static::getFixedProvider($logistic_provider);
        $model->logistic_provider_fixed = $logistic_provider_fixed;
        $model->create_time = time();

        if($model->save()){
            //update box order status as to_user
            BoxOrder::UpdateBoxOrderStatus($box_id,$order_id);

            $storage = Storage::find()->where(['order_id'=>$model->order_id])->one();
            if(!empty($storage)){
                $storage->logistic_id = $model->id;
                $storage->status = 'out';
                $storage->out_time = time();

                $storage->update();
            }
            
            return $model;
        }
        return false;
    }
    /**
     * after save logistic need send notify
     * @param  [type] $insert          [description]
     * @param  [type] $changeAttribute [description]
     * @return [type]                  [description]
     */
    public function afterSave($insert,$changeAttribute){
        //delete wait out order 
        WaitOutput::deleteAll(['order_id'=>$this->order_id,'express_no'=>$this->logistic_no]);
        //update order status
        $order = Order::findOne($this->order_id);
        $order->status = 'to_user';
        $order->update_time = time();
        if($order->update()){
            //save order log
            OrderLog::saveLog($order, '', 'admin', Yii::$app->user->id);
        }
        //register logistic
        $this->registerLogic($this->logistic_no, $this->logistic_provider_fixed);


        // $order = Order::findOne($this->order_id);
        $stock = Stock::findOne($order->stock_id);
        $notifyTpl = "商品“%stockName%”已经国内发出，很快会送到您的手里面哦～您可以在订单页直接跟踪物流信息";
        $notifyStr = str_replace('%stockName%', $stock ? "“".$stock->name."”" : "", $notifyTpl);
        Yii::$app->notify->sendNotification($order->user_id,['title'=>$notifyStr,'type'=>'trade','from'=>'trade',
            'data'=>[
                'order_id'=>$order->id,
                'trade_title'=>$order->statusDesc(),
                'stock_imageUrl'=>$stock->imgs?json_decode($stock->imgs,true)[0]:[],
                'jumpURL'=>'AMCustomerURL://showorderdetail?id='.$order->id,
            ]
        ],0);
    }
    /**
     * [getFixedProvider description]
     * @param  string $logistic_provider [description]
     * @return [type]                    [description]
     */
    public static function getFixedProvider($logistic_provider = '') {
         $logistic_provider = str_replace(' ', '', $logistic_provider);
         if( false !== strpos($logistic_provider, '顺丰') ) return 'shunfeng';
         if( false !== strpos($logistic_provider, '圆通') ) return 'yuantong';

         return '';
    }
    /**
     * register express 100
     * @param  [type] $logistic_no       [description]
     * @param  [type] $logistic_provider [description]
     * @param  string $to                [description]
     * @return [type]                    [description]
     */
    public function registerLogic($logistic_no, $logistic_provider, $to='') {
        $logistic_no = str_replace(' ', '', $logistic_no);
        if(empty($logistic_no)) return false;

        $config = array(
            'shunfeng' => 'shunfeng',
            'yuantong' => 'yuantong',
            'ems' => 'ems',
            'emsinten' => 'emsinten',
            'usps' => 'usps',
            'letseml' => 'letseml',
            'xlobo' => 'xlobo',
            'chronopostfra' => 'chronopostfra',
            'dhl' => 'dhl',
            'meiguokuaidi' => 'meiguokuaidi',
            'shentong' => 'shentong',
            //'' => '',
        );
        //不在推送范围内的快递单号，不会推送到kuaidi100
        if(!isset($config[$logistic_provider])) return false;

        $callback_url = 'http://api.taoshij.com/logistic/logisticUpdateCallback';
        $param = '{"company":"'.$logistic_provider.'", "number":"'.$logistic_no.'","from":"", "to":"'.$to.'", "key":"'.self::$KUAIDI100_KEY.'", "parameters":{"callbackurl":"'.$callback_url.'"}}';
        $params = array( 'schema'=>'json', 'param'=>$param );
        $url = "http://www.kuaidi100.com/poll";
        $result = self::sendHttpRequest($url, $params, 1);

        return isset($result['returnCode'])&&'200'==$result['returnCode'] ? true : false;
    }
    //发送http request请求
    //支持GET和POST类型
    //return array('code', 'msg')
    public static function sendHttpRequest($url, $params=array(), $post = 0) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
        curl_setopt ( $ch, CURLOPT_HEADER, 0 );
        curl_setopt ( $ch, CURLOPT_URL, $url );
        if($post) {
            $str = "";
            foreach ( $params as $k => $v ) {
                $str .= "$k=" . urlencode ( $v ) . "&";
            }
            $post_data = substr( $str, 0, - 1 );
            curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post_data );
            curl_setopt($ch, CURLOPT_POST, 1);
        }
        $result = curl_exec($ch);
        curl_close($ch);

        //var_dump($result); exit;
        $response = empty($result) ? array() : json_decode($result, true);

        return $response;
    }
}