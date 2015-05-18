<?php
ini_set('date.timezone','Asia/Shanghai');
include __DIR__."/../library/Jf/Registry.php";
include __DIR__."/../library/Weixin/Qyapi.php";

define('CORPID', "wx382ee341be977eaa");
define('CORPSECRET', "CrAfylZsr64p1KtgS26jRz-fYNq5ZTO2ltFuJWJ7u6R-Qdd5X1tZLjclG0NJ_1rR");
define('AGENTID', isset($_GET['agentid']) ? $_GET['agentid'] : 4);

$config = array(
    'memcached' => array('host' => '127.0.0.1', 'port' => '11211')
);

$memConfig = $config['memcached'];
$memcached = new Memcached();
$memcached->addServer($memConfig['host'], $memConfig['port']);
Registry::set('memcached', $memcached);

//$memcached->flush();
session_start();
$userInfoKey = 'session::userInfo::' . session_id();
$userInfo = $memcached->get($userInfoKey);
if (!$userInfo) {
    if (empty($_GET['code'])) {
        Qyapi::oauthRedirect();
    } else {
        $userInfo = Qyapi::getUserInfo($_GET['code']);
        if (empty($userInfo['errcode'])) {
            $memcached->set($userInfoKey, $userInfo, 60 * 60 * 24);
            echo $userInfo;exit;
        }
    }
}


if (isset($_GET['action']) && strlen($_GET['action']) < 20) {
    $staticsHtml = '/statics/'.$_GET['action'].'.html?user_id='.$userInfo['user_id'];
    header("Location: $staticsHtml");
} else {
    echo $userInfo;exit;
}


