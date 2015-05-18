<?php
namespace common\components;
class AHelper extends \yii\helpers\ArrayHelper{
    /**
     * function_description
     *
     *
     * @return
     */
    public static function generateUniqueId() {
        $prefix = substr(md5(gethostname()), 0, 5);
        return uniqid($prefix);
    }

    public static function urlBase64Encode($str){
        return strtr(base64_encode($str), "+/=", "-_.");
    }
    public static function urlBase64Decode($str){
        return base64_decode(strtr($str, "-_.","+/="));
    }

    /**
     * function_description
     *
     *
     * @return
     */
    public static function generateRandomString($length = 10) {
        $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWSXYZ";
        $randomString = "";
        for($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    protected static function randomReadLine($fileName, $buffer_length = 4096) {
        $handle = @fopen($fileName, "r");
        if ($handle) {
            $random_line = null;
            $line = null;
            $count = 0;
            while (($line = fgets($handle, $buffer_length)) !== false) {
                $count++;
                // P(1/$count) probability of picking current line as random line
                if(rand() % $count == 0) {
                    $random_line = $line;
                }
            }
            if (!feof($handle)) {
                fclose($handle);
                return null;
            } else {
                fclose($handle);
            }
            return $random_line;
        }
    }
    public static function cutstr($string,$length,$etc="..."){
        $result = '';
        $string = html_entity_decode(trim(strip_tags($string)), ENT_QUOTES, 'UTF-8');
        $strlen = strlen($string);
        for ($i = 0; (($i < $strlen) && ($length > 0)); $i++){
            if ($number = strpos(str_pad(decbin(ord(substr($string, $i, 1))), 8, '0', STR_PAD_LEFT), '0')){
                if ($length < 1.0){
                    break;
                }
                $result .= substr($string, $i, $number);
                $length -= 1.0;
                $i += $number - 1;
            }else{
                $result .= substr($string, $i, 1);
                $length -= 0.5;
            }
        }
        $result = htmlspecialchars($result, ENT_QUOTES, 'UTF-8');
        if ($i < $strlen){
            $result .= $etc;
        }
        return $result;
    }
    public static function map_array($array,$group)
    {
        $result = [];
        foreach ($array as $element) {
                $result[static::getValue($element, $group)][] = $element;
        }

        return $result;
    }
    /**
     * curl for post json
     * @param  json data
     * @param  [type] $url  [description]
     * @return [type]       [description]
     */
    public static function curl_json($data,$url){

        // $ch = curl_init() ;
        // //set the url, number of POST vars, POST data
        // curl_setopt($ch, CURLOPT_URL, $url) ;
        // if(is_array($data)){
        //     curl_setopt($ch, CURLOPT_POST, count($data)); 
        // }else{
        //     curl_setopt($ch, CURLOPT_POST, 1); 
        // }
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        // ob_start();
        // curl_exec($ch);
        // $result = ob_get_contents() ;
        // ob_end_clean();
        // curl_close($ch) ;

        // return $result;


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        // $header = array("'Content-type:  application/json','Content-Length: ' . strlen($data))");
        // curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
        curl_setopt($ch, CURLOPT_SSLCERT, $this->certFile);
        curl_setopt($ch, CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $result=curl_exec ($ch);
        var_dump(curl_error($ch)); 
        curl_close ($ch);
        return $result;

        //open connection
        // $ch = curl_init();
        // $fields_string = '';
        // foreach($data as $key=>$value) {
        //     $fields_string .= $key.'='.$value.'&'; 
        // }
        // rtrim($fields_string, '&');
        // //set the url, number of POST vars, POST data
        // // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        // curl_setopt($ch, CURLOPT_SSLVERSION, 3);
        // curl_setopt($ch,CURLOPT_URL, $url);
        // curl_setopt($ch,CURLOPT_POST, count($data));
        // curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

        // //execute post
        // $result = curl_exec($ch);
        // var_dump(curl_error($ch)); 
        // var_dump($result);exit;

        // //close connection
        // curl_close($ch);

        // return $result;
    }
}