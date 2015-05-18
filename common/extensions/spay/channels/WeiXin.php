<?php
namespace common\extensions\spay\channels;

use Yii;
use common\components\AHelper;
class WeiXin extends PayChannelAbstract
{
    public $channel_name = 'wx_client';

    protected $_sign_type;

    protected $_appid;

    protected $_refund_url;

    protected $_cert;

    protected $_certkey;

    protected $_certPasswd;

    protected $_mchid;

    protected $_caFile;

    public $certType = 'PEM';

    public $input_charset = 'UTF-8';

    public $service_version = '1.0';

    protected $_partner;

    protected $_op_user_passwd;

    public function init()
    {
        parent::init();
        // $this->_appid = trim($this->_config['app_id']);
        $this->_key = trim($this->_config['key']);
        $this->_sign_type = trim($this->_config['sign_type']);
        $this->_refund_url = trim($this->_config['refund_url']);
        $this->_cert = (Yii::$app->refund->config_path . "/1217927701.pem");
        $this->_caFile = (Yii::$app->refund->config_path . "/cacert.pem");
        // $this->_certPasswd = $this->_config['cert_pwd'];
        $this->_partner = trim($this->_config['mch_id']);
        $this->_op_user_passwd = $this->_config['passwd'];
    }
    /**
     * do refund
     * @return [type] [description]
     */
    public function refund(){
        $params = [
            // 'input_charset'=>'UTF-8',
            'service_version'=>$this->service_version,
            'partner'=>$this->_partner,
            'transaction_id'=>$this->_platform_trade_no,
            'out_refund_no'=>$this->_refund_id,
            'total_fee'=>$this->_total_fee * 100,
            'refund_fee'=>$this->_refund_amount * 100,
            'op_user_id'=>$this->_partner,
            'op_user_passwd'=>$this->_op_user_passwd,
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

        $arg  = "";
        while (list ($key, $val) = each ($params)) {
            $arg.=$key."=".$val."&";
        }
        $arg = substr($arg,0,count($arg)-2);
        
        if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}
        $url = $this->_refund_url.'?'.$arg;
        $filename = Yii::$app->params['log_path'].'/'.'weixin_refund.txt';
        $data = '订单号:'.$this->_order->id.' 订单总额:'.($this->_total_fee * 100) .' 退款金额:'.($this->_refund_amount * 100)."\r\n";
        file_put_contents($filename,$data,FILE_APPEND);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, '2');
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, '1');
        curl_setopt($ch, CURLOPT_SSLCERT, $this->_cert);
        curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM');
        curl_setopt($ch, CURLOPT_SSLCERTPASSWD,$this->_partner);

        // curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);

        $xml = simplexml_load_string($response);
        $ret = (int)$xml->retcode;

        if($ret == 0){
            $code = self::REFUND_CALLBACK_IS_TRUE;
            $error = "";
        } else{
            $code = self::REFUND_CALLBACK_IS_FAIL;
            $error = (string)$xml->retmsg;
        }
        
        return [$code,$error];
    }
    /**
     * @param $prestr 
     * @param $key 
     * return sign result
     */
    function md5Sign($prestr, $key) {
        $prestr = $prestr.'&key='.$key;
        return strtoupper(md5($prestr));
    }

}