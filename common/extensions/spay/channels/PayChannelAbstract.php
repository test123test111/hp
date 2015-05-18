<?php
namespace common\extensions\spay\channels;

use Yii;
use common\components\GHelper;
use common\extensions\spay\Payment;
use common\models\Order;

abstract class PayChannelAbstract
{

    const REFUND_CALLBACK_IS_TRUE = 0;
    const REFUND_CALLBACK_IS_FAIL = 11111;
    public $channel_name = '';

    protected $_method_id = null;
    protected $_order = null;
    protected $_refund_amount = null;
    protected $_platform_trade_no = null;
    protected $_config = null;
    protected $_refund_id = null;
    protected $_total_fee = null;

    public function __construct()
    {
        $this->init();
    }


    public function init()
    {
        $this->loadConfig();
    }


    public function loadConfig()
    {
        $this->_config = require(Yii::$app->refund->config_path . "/channel_" . $this->channel_name . ".php");
    }
    /**
     * get request url and data for submit to pay channel
     * @return [request_url, data] or error
     */
    abstract public function refund();

    public function setOrder($order)
    {
        $this->_order = $order;

        return $this;
    }

    public function setRefundAmount($refund_amount)
    {
        $this->_refund_amount = $refund_amount;

        return $this;
    }

    public function setPlatfromTradeNo($trade_no)
    {
        $this->_platform_trade_no = $trade_no;

        return $this;
    }

    public function setRefundId($refund_id)
    {
        $this->_refund_id = $refund_id;

        return $this;
    }

    public function setTotalFee($total_fee)
    {
        $this->_total_fee = $total_fee;

        return $this;
    }


    public function getReturnMsg($ret_body)
    {
        return "";
    }
    /**
     * build weixin params
     * @param $para_temp 
     * @return 
     */
    protected function buildRequestPara($para_temp) {
        //除去待签名参数数组中的空值和签名参数
        $para_filter = $this->paraFilter($para_temp);

        //对待签名参数数组排序
        $para_sort = $this->argSort($para_filter);

        //生成签名结果
        $mysign = $this->buildRequestMysign($para_sort);
        
        //签名结果与签名方式加入请求提交参数组中
        $para_sort['sign'] = $mysign;
        
        return $para_sort;
    }
    /**
     * remove empty value
     * @param $para 
     * return 
     */
    protected function paraFilter($para) {
        $para_filter = array();
        while (list ($key, $val) = each ($para)) {
            if($key == "sign" || $key == "sign_type" || $val == "")continue;
            else    $para_filter[$key] = $para[$key];
        }
        return $para_filter;
    }
    /**
     * sort array
     * @param $para 
     * return 
     */
    public function argSort($para) {
        ksort($para);
        reset($para);
        return $para;
    }

    /**
     * 生成签名结果
     * @param $para_sort 已排序要签名的数组
     * return 签名结果字符串
     */
    public function buildRequestMysign($para_sort) {
        //'&='
        $prestr = $this->createLinkstring($para_sort);
        $mysign = "";
        switch (strtoupper(trim($this->_sign_type))) {
            case "MD5" :
                $mysign = $this->md5Sign($prestr, $this->_key);
                break;
            default :
                $mysign = "";
        }
        
        return $mysign;
    }
    /**
     * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
     * @param $para 需要拼接的数组
     * return 拼接完成以后的字符串
     */
    public function createLinkstring($para) {
        $arg  = "";
        while (list ($key, $val) = each ($para)) {
            $arg.=$key."=".$val."&";
        }
        $arg = substr($arg,0,count($arg)-2);
        
        if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}
        
        return $arg;
    }

}