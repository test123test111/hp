<?php
namespace common\extensions\spay;

use Yii;
use yii\base\Component;
use common\models\UserRefund;
use backend\models\Order;
use backend\models\OrderLog;
use backend\models\Payment;
class Refund extends Component
{
    const REFUND_ID_IS_WRONG = 11001;
    const PAYMENT_IS_WRONG = 11002;
    const REFUND_IS_FAIL = 11003;
    const ORDER_STATUS_IS_WRONG = 11004;
    const REFUND_IS_SUCCESS = 0;

    public $config_file;

    public $config_path;
    protected $_configs = [];
    public function init()
    {
        // Register the spay path alias
        if (Yii::getAlias("spay") === false) {
            Yii::setAlias("spay", realpath(dirname(__FILE__)));
        }
        if (is_null($this->config_file)) {
            throw new \Exception('Payment config_file can not be null');
        }
        if (is_null($this->config_path)) {
            $this->config_path = dirname($this->config_file);
        }
        $this->loadConfig();

        parent::init();
    }
    /**
     * load config
     */
    protected function loadConfig()
    {
        $this->_configs = require($this->config_file);
    }
    /**
     * do refund 
     * from order data request gateway
     * @param  table user_refund primay id 
     * @return [type] [description]
     */
    public function doRefund($refund_id){
        $userRefund = UserRefund::find()->where(['id'=>$refund_id,'status'=>UserRefund::STATUS_UNHANDLE,'type'=>UserRefund::REBACK_BY_SOURCE])->one();
        // $userRefund = UserRefund::find()->where(['id'=>$refund_id,'type'=>UserRefund::REBACK_BY_SOURCE])->one();
        if(empty($userRefund)){
            return [false,'退款记录不存在',self::REFUND_ID_IS_WRONG];
        }
        //被退款的订单
        $orderInfo = Order::findOne($userRefund->order_id);
        // if(!empty($orderInfo) || $orderInfo->status != Order::ORDER_STATUS_IS_WAITREFUND){
        //     return [false,'订单状态不正确',self::ORDER_STATUS_IS_WRONG];
        // }
        if($userRefund->range == UserRefund::REFUND_IS_FULL){
            $refund_amount = $orderInfo->getNeedRefundAmount();
        }else if($userRefund->range == 1){
            $refund_amount = $userRefund->amount;
        }
        $payment = Payment::findOne($orderInfo->pre_payment_id);
        if(empty($payment)){
            return [false,'false',self::PAYMENT_IS_WRONG];
        }

        $maxRefundAmount = $payment->amount - $payment->refund_amount;
        $refundAmount = $maxRefundAmount >= $refund_amount ? $refund_amount : $maxRefundAmount;
        // if($userRefund->range == UserRefund::REFUND_IS_PART) {
        //     return $userRefund->amount;
        // } else {
        //     $payments = Payment::find()->where(['id'=>$orderInfo->pre_payment_id])->all();
        //     $retStr = 0;
        //     foreach($payments as $payment_one) {
        //         if($payment_one->status == 'payed') {
        //             $retStr += $payment_one->amount;
        //         }
        //     }
        //     $retStr = $orderInfo->sum_price >= $retStr ? $retStr : $orderInfo->sum_price;
        //     // return sprintf("%.2f", $retStr);
        //     $refundAmount = sprintf("%.2f", $retStr);
        // }
        if(empty($payment)){
            return [false,'false',self::PAYMENT_IS_WRONG];
        }
        $channel = $this->getChannelByMethod($payment->source);
        if($channel != ""){
            $channelMapper = new $channel;
            $channelMapper->setOrder($orderInfo);
            $channelMapper->setRefundAmount($refundAmount);
            $channelMapper->setPlatfromTradeNo($payment->platform_trade_no);
            $channelMapper->setRefundId(date("YmdHis").$refund_id);
            $channelMapper->setTotalFee($payment->amount);
            list($code,$msg) = $channelMapper->refund();
            if($code == $channel::REFUND_CALLBACK_IS_TRUE){
                if($payment->refund_amount == null){
                    $payment->refund_amount = $refundAmount;
                    $payment->update();
                }else{
                    $payment->updateCounters(['refund_amount' => $refundAmount]);
                }
                $userRefund->status = UserRefund::STATUS_SUCC;
                $userRefund->update_time = time();
                $userRefund->update();

                $status = Order::ORDER_STATUS_IS_REFUND;
                if($orderInfo->status != Order::ORDER_STATUS_IS_FULLREFUND){
                    if($orderInfo->pay_type == Order::ORDER_PAY_TYPE_IS_FULL){
                        $status = Order::ORDER_STATUS_IS_FULLREFUND;
                    }elseif($orderInfo->pay_type == Order::ORDER_PAY_TYPE_IS_PART){
                        $status = Order::ORDER_STATUS_IS_REFUND;
                    }
                }
                $orderInfo->status = $status;
                $orderInfo->update_time = time();
                $orderInfo->update();
                OrderLog::saveLog($orderInfo, '', 'system', 0);
                return [true,'',self::REFUND_IS_SUCCESS];
            }else{
                $payment->refund_memo = $msg;
                $payment->update();

                $userRefund->status = UserRefund::STATUS_FAIL;
                $userRefund->update_time = time();
                $userRefund->update();
                return [false,$msg,self::REFUND_IS_FAIL];
            }
        }
    }
    /**
     * @param $pay_method
     * @return string
     */
    protected function getChannelByMethod($pay_method)
    {
        if (isset($this->_configs['channels'][$pay_method])) {
            return $this->_configs['channels'][$pay_method]['className'];
        }
        return "";
    }
}