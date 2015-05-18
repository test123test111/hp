<?php

namespace Weixin;

use Jf\Registry;

class Qyapi {

    const BASE_URL = "https://qyapi.weixin.qq.com/cgi-bin";
    const TOKEN_URL = "%s/gettoken?corpid=%s&corpsecret=%s";

    public static function getAccessToken() {
        $memcached = Registry::get('memcached');
        $accessToken = $memcached->get('qyapi::access_token');
        if (!$accessToken) {
            $tokenUrl = sprintf(self::TOKEN_URL, self::BASE_URL, CORPID, CORPSECRET);
            $apiResult = json_decode(file_get_contents($tokenUrl), true);
            $accessToken = $apiResult['access_token'];
            $memcached->set('qyapi::access_token', $accessToken, 60 * 20);
        }
        return $accessToken;
    }

    const USER_INFO_URL = '%s/user/getuserinfo?access_token=%s&code=%s&agentid=%s';

    public static function getUserInfo($code) {
        $baseUrl = self::BASE_URL;
        $accessToken = self::getAccessToken();
        $userInfoUrl = sprintf(self::USER_INFO_URL, $baseUrl, $accessToken, $code, AGENTID);
        $userInfo = file_get_contents($userInfoUrl);
        return $userInfo;
    }

    public static function oauthRedirect($redirect_uri = NULL, $scope = 'snsapi_base') {
        if (!$redirect_uri) {
            $redirect_uri = "http://" . $_SERVER ['HTTP_HOST'] . $_SERVER['PHP_SELF'];
        }
        $urlencode_redirect_uri = urlencode($redirect_uri);
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?" .
                "appid=" . CORPID . "&redirect_uri=$urlencode_redirect_uri" .
                "&response_type=code&scope=$scope&state=STATE#wechat_redirect";
        header("Location: $url");
    }

}
