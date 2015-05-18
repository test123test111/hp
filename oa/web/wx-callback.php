<?php

include_once __DIR__."/../library/Weixin/Pyapi/WXBizMsgCrypt.php";


$encodingAesKey = "WKz2d7EZlNnUGj8mRmjFhdPto5bRFBsAYKANo83XXVu";
$token = "4fTJuNL3SpxgoveCE1qC7";
$corpId = "wx382ee341be977eaa"; 


$sVerifyMsgSig = isset($_GET["msg_signature"])? $_GET["msg_signature"] : '';
$sVerifyTimeStamp = isset($_GET["timestamp"])? $_GET["timestamp"] : '';
$sVerifyNonce = isset($_GET["nonce"]) ? $_GET["nonce"] : '';
$sVerifyEchoStr = isset($_GET["echostr"]) ? $_GET["echostr"] : '';

$EchoStr = "";

$wxcpt = new WXBizMsgCrypt($token, $encodingAesKey, $corpId);
$errCode = $wxcpt->VerifyURL($sVerifyMsgSig, $sVerifyTimeStamp, $sVerifyNonce, $sVerifyEchoStr, $sEchoStr);
if ($errCode == 0) {
    echo $sEchoStr;
} else {
	print("ERR: " . $errCode . "\n\n");
}