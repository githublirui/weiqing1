<?php
//print_R($_FILES);
global $_GPC, $_W;
ini_set('date.timezone','Asia/Shanghai');
checkauth();
if($_W['fans']){
	
}else{
	exit();
}
$orderid = (INT)$_GPC['orderid'];
if($orderid){
	$orderinfo = pdo_get('hangyi_order', array('id' => $orderid,'buy_openid'=>$_W['fans']['openid']));
}
if(!$orderinfo){
	exit();
}
if($orderinfo['sell_id'] && $orderinfo['sell_openid']){
	$sell_info = pdo_get('hangyi_user', array('uid' => $orderinfo['sell_id']));
}
if($sell_info['nickname'] && $sell_info['openid']==$orderinfo['sell_openid']){
	
}else{
	exit();
}

$productinfo = pdo_get('hangyi_product', array('id' =>$orderinfo['pid']));
//error_reporting(E_ERROR);
require_once "../addons/wxz_easy_pay/pay/lib/WxPay.Api.php";
require_once "../addons/wxz_easy_pay/pay/example/WxPay.JsApiPay.php";
//require_once '../addons/wxz_easy_pay/pay/example/log.php';

//初始化日志
//$logHandler= new CLogFileHandler("../logs/".date('Y-m-d').'.log');
//$log = Log::Init($logHandler, 15);

//打印输出数组信息
/*function printf_info($data)
{
    foreach($data as $key=>$value){
        echo "<font color='#00ff55;'>$key</font> : $value <br/>";
    }
}*/

//①、获取用户openid
$tools = new JsApiPay();
//$openId = $tools->GetOpenid();
$openId = $_W['fans']['openid'];

//②、统一下单
$input = new WxPayUnifiedOrder();
$input->SetBody("test");
$input->SetAttach("test");
$input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
$input->SetTotal_fee($orderinfo['goodsPriceTotal']*100);
$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 600));
$input->SetGoods_tag("test");
$input->SetNotify_url("http://paysdk.weixin.qq.com/example/notify.php");
$input->SetTrade_type("JSAPI");
$input->SetOpenid($openId);
$order = WxPayApi::unifiedOrder($input);
//echo '<font color="#f00"><b>统一下单支付单信息</b></font><br/>';
//printf_info($order);
$jsApiParameters = $tools->GetJsApiParameters($order);

//获取共享收货地址js函数参数
$editAddress = $tools->GetEditAddressParameters();

//③、在支持成功回调通知中处理成功之后的事宜，见 notify.php
/**
 * 注意：
 * 1、当你的回调地址不可访问的时候，回调通知会失败，可以通过查询订单来确认支付是否成功
 * 2、jsapi支付时需要填入用户openid，WxPay.JsApiPay.php中有获取openid流程 （文档可以参考微信公众平台“网页授权接口”，
 * 参考http://mp.weixin.qq.com/wiki/17/c0f37d5704f0b64713d5d2c37b468d75.html）
 */


include $this->template('dopay');

?>




