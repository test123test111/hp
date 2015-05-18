<?php
namespace common\extensions\spay\channels;

use Yii;
use common\components\AHelper;
class Alipay extends PayChannelAbstract
{
	public $channel_name = 'zfb';

	public $input_charset = 'utf-8';

	public $notify_url = '';

	protected $_service;

	public function init()
    {
        parent::init();
        $this->_partner = trim($this->_config['partner']);
        $this->_key = trim($this->_config['key']);
        $this->_sign_type = trim($this->_config['sign_type']);
        $this->_refund_url = trim($this->_config['refund_url']);
        $this->_service = trim($this->_config['service']);
        // $this->_cert = require_once(Yii::$app->refund->config_path . "/alipay_cacert.pem");
    }
    /**
     * do refund
     * @return [type] [description]
     */
    public function refund(){

        $params = [
            "service"           => $this->_service,          
            "partner"           => $this->_partner,                
            "_input_charset"    => $this->input_charset,           
            "sign_type"         => $this->_sign_type,
            "batch_no"          => $this->_refund_id,
            "refund_date"       => date("Y-m-d H:i:s"),
            // "notify_url"        => 'http://101.66.253.168:8080/user/order/notifyRefund',
            "batch_num"         => 1,
            "detail_data"       => implode('^', [$this->_platform_trade_no, $this->_refund_amount, 'customer request']),
        ];
        return $this->getOutData($params);
    }
    /**
     * [getOutData description]
     * @return [type] [description]
     */
    public function getOutData($params){
        $data = $this->buildRequestPara($params);
        $params['sign'] = $data['sign'];
        // $result = AHelper::curl_json($data,$this->_refund_url);
        $filename = Yii::$app->params['log_path'].'/'.'alipay_refund.txt';
        $data = '订单号:'. $this->_order->id.' 退款时间:'.date('Y-m-d H:i:s')."\r\n";
        file_put_contents($filename,$data,FILE_APPEND);
        $ch = curl_init() ;
        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $this->_refund_url) ;
        curl_setopt($ch, CURLOPT_POST, count($params)) ; // 启用时会发送一个常规的POST请求，类型为：application/x-www-form-urlencoded，就像表单提交的一样。
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params); // 在HTTP中的“POST”操作。如果要传送一个文件，需要一个@开头的文件名
        ob_start();
        curl_exec($ch);
        $curlResult = ob_get_contents() ;
        ob_end_clean();
        curl_close($ch) ;

        $xml = simplexml_load_string($curlResult);
        $ret = (string)$xml->is_success;
        if($ret == 'T'){
        	$code = self::REFUND_CALLBACK_IS_TRUE;
        	$error = "";
        } else if($ret == 'F'){
        	$code = self::REFUND_CALLBACK_IS_FAIL;
            $error = (string)$xml->error;
        } else if($ret == 'P') {    

        }
        
        return [$code,$error];
    }

    
    /**
     * @param $prestr 
     * @param $key 
     * return sign result
     */
    function md5Sign($prestr, $key) {
        $prestr = $prestr . $key;
        return md5($prestr);
    }
}