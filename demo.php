<?php
include_once "wxBizDataCrypt.php";

$appid = '这里填入你自己的';//小程序开发者的appid
$secret = '总共两个这个也要填其他不用管';//小程序开发者的secret

$code = isset($_POST['code'])?$_POST['code']:'';
$encryptedData=isset($_POST['encryptedData'])?$_POST['encryptedData']:'';
$iv = isset($_POST['iv'])?$_POST['iv']:'';

$grant_type = "authorization_code"; //授权（必填）
$params = "appid=".$appid."&secret=".$secret."&js_code=".$code."&grant_type=".$grant_type;
$url = "https://api.weixin.qq.com/sns/jscode2session?".$params;
$res = json_decode(httpGet($url),true);//$res = json_decode($this->httpGet($url),true);//类内的用法******************
//json_decode不加参数true，转成的就不是array,而是对象。
$sessionKey = $res['session_key'];//取出json里对应的值

$pc = new WXBizDataCrypt($appid, $sessionKey);
$errCode = $pc->decryptData($encryptedData,$iv,$data);

if ($errCode == 0) {
    print($data . "\n");
} else {
    print($errCode . "\n");
}
function httpGet($url) {
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_TIMEOUT, 500);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($curl, CURLOPT_URL, $url);
	$res = curl_exec($curl);
	curl_close($curl);
	return $res;
}