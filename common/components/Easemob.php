<?php
namespace common\components;
use Yii;
class Easemob{
    private function __construct(){
        $this->base_url="https://a1.easemob.com/".Yii::$app->params['easemob_org']."/".Yii::$app->params['easemob_app']."/";
    }
    public static $_instance;
    public static function getInstance(){
        if(!isset(self::$_instance)){
            self::$_instance=new self();
        }
        return self::$_instance;
    }
    public function getLatestMsg($cursor=null){
        $token=$this->token();
        $params=['limit'=>20,"ql"=>"order by timestamp desc"];
        if($cursor){
            $params['cursor']=$cursor;
        }
        return $this->curl('get',"chatmessages?".http_build_query($params),
            null,["Authorization: Bearer {$token['access_token']}"]);
    }
    public function deleteUser($username){
        $token=$this->token();
        return $this->curl('delete',"users/$username",
            null,["Authorization: Bearer {$token['access_token']}"]);
    }
    public function createUser($username,$password){
        $token=$this->token();
        return $this->curl('post','users',
            ['username'=>$username,
            'password'=>$password],["Authorization: Bearer {$token['access_token']}"]);
        //Authorization: Bearer YWMt39RfMMOqEeKYE_GW7tu81AAAAT71lGijyjG4VUIC2AwZGzUjVbPp_4qRD5k
    }
    public function sendMsg($target,$msg,$type='admin',$from='admin',$data=[]){
        $token=$this->token();
        if(is_string($target)){
            $target=[$target];
        }
        $data['type']=$type;

        return $this->curl('post','messages',
            [
                'target_type'=>"users",
                'target'=>$target,
                'msg'=>['type'=>'txt','msg'=>$msg],
                'from'=>$from,
                'ext'=>$data,
            ],
            ["Authorization: Bearer {$token['access_token']}"]);
        
    }
    private $token;
    public function token(){
        if(isset($this->token)){
            return $this->token;
        }
        $file = Yii::getAlias('@app') ."/tmp/easemob_token";
        // $file=ROOT_PATH."/tmp/easemob_token";
        if(file_exists($file)){
            if($token_json=file_get_contents($file)){
                $this->token=json_decode($token_json,true);
                if($this->token&&
                    $this->token['create_time']+min($this->token['expires_in'],86400)>time()){
                    return $this->token;
                }
            }
        }
        $this->token=$this->curl('post',"token",[
            'grant_type'=>'client_credentials',
            'client_id'=>Yii::$app->params['easemob_client_id'],
            'client_secret'=>Yii::$app->params['easemob_secret'],
            ]);
        $this->token['create_time']=time();
        $oldmask=umask(0000);
        file_put_contents($file,json_encode($this->token));
        umask($oldmask);
        return $this->token;
    }

    private function curl($method,$url,$body=null,$header=null) {
        $url=$this->base_url."$url";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
        curl_setopt($ch, CURLOPT_URL, $url);
        if($body){
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
		//output时忽略http响应头
        curl_setopt($ch, CURLOPT_HEADER, false);
		//设置http请求的头部信息 每行是数组中的一项
        //当url中用ip访问时，允许用host指定具体域名
        if ($header) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            //curl_setopt($ch, CURLOPT_HTTPHEADER, array('SOAPAction: ""'));
        }

        $res = curl_exec($ch);

        return json_decode($res,true);
    }
}
