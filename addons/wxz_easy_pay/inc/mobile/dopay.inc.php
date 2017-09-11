<?php

//print_R($_FILES);
global $_GPC, $_W;
$mobile_url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
$mobile_url = str_replace("/app/index.php", "", $mobile_url);
$mobile_url = $mobile_url . "/addons/wxz_easy_pay/zhifu/notify.php";
ini_set('date.timezone', 'Asia/Shanghai');
$uniacid = $_W['uniacid'];
load()->model('mc');
$fan = mc_fansinfo($_W['openid']);
if (!empty($fan) && !empty($fan['openid'])) {
    $userinfo = $fan;
} else {
    $userinfo = mc_oauth_userinfo();
}


$i = $_GPC['i'];
$gzhinfo = $_W['cache']['uniaccount:' . $i];


$orderid = (INT) $_GPC['orderid'];
if ($orderid) {
    $orderinfo = pdo_get('hangyi_order', array('id' => $orderid, 'buy_openid' => $userinfo['openid']));
}
if (!$orderinfo) {
    exit();
}
if ($orderinfo['sell_id'] && $orderinfo['sell_openid']) {
    $sell_info = pdo_get('hangyi_user', array('uid' => $orderinfo['sell_id']));
}
if ($sell_info['nickname'] && $sell_info['openid'] == $orderinfo['sell_openid']) {
    
} else {
    exit();
}

$peizhi = pdo_get('hangyi_peizhi', array('uniacid' => $uniacid));

$pids = $orderinfo['pids'];
$pids = explode(',', $pids);
$productinfos = array();
foreach($pids as $pid) {
   $productinfos[$pid] = pdo_get('hangyi_product', array('id' => $pid));
   if ($productinfo['goodsStock'] <= 0) {
        message('该商品已经售完');
        exit();
   }
}

//$goodsPriceTotal = $productinfo['goodsPrice']*$orderinfo['goodsNum'];
$goodsPriceTotal = $orderinfo['goodsPriceTotalReal'];


if (!$orderinfo['out_trade_no']) {
    $trade_no = date("YmdHis") . rand(10000, 99999);
    $result = pdo_query("UPDATE " . tablename('hangyi_order') . " SET `out_trade_no`='" . $trade_no . "'  WHERE id = '" . $orderinfo['id'] . "' limit 1");
} else {
    $trade_no = $orderinfo['out_trade_no'];
}



$pay_log = pdo_get('core_paylog', array('tid' => $orderinfo['id'], 'type' => 'wechat', 'module' => $_GPC['m']));

if ($pay_log['status']) {
    message('订单已经支付', '', 'success');
    exit();
}
$goodsPriceTotal = floatval($goodsPriceTotal);
if ($goodsPriceTotal <= 0) {
    message('支付错误, 金额小于0');
}
$params = array(
    'tid' => $orderinfo['id'], //充值模块中的订单号，此号码用于业务模块中区分订单，交易的识别码
    'ordersn' => $trade_no, //收银台中显示的订单号
    'title' => $productinfo['goodsName'], //收银台中显示的标题
    'fee' => $goodsPriceTotal, //收银台中显示需要支付的金额,只能大于 0
    'user' => $_W['member']['uid'], //付款用户, 付款的用户名(选填项)
    'encrypt_code' => time(), //付款用户, 付款的用户名(选填项)
);
//调用pay方法
$this->pay($params);


exit();

/*
  define("ZAPPID",$gzhinfo['key']);
  define("MCHID_D",$peizhi['mchid']);
  define("MC_KEY_D",$peizhi['mc_key']);
  define("MSECRET",$gzhinfo['secret']);
  //error_reporting(E_ERROR);
  require_once "../addons/wxz_easy_pay/pay/lib/WxPay.Api.php";
  require_once "../addons/wxz_easy_pay/pay/example/WxPay.JsApiPay.php";
  //require_once '../addons/wxz_easy_pay/pay/example/log.php';

  //初始化日志
  //$logHandler= new CLogFileHandler("../logs/".date('Y-m-d').'.log');
  //$log = Log::Init($logHandler, 15);

  //打印输出数组信息
  function printf_info($data)
  {
  foreach($data as $key=>$value){
  echo "<font color='#00ff55;'>$key</font> : $value <br/>";
  }
  }

  //①、获取用户openid
  $tools = new JsApiPay();
  //$openId = $tools->GetOpenid();
  $openId = $_W['fans']['openid'];

  //②、统一下单
  $input = new WxPayUnifiedOrder();
  $input->SetBody($productinfo['goodsName']);
  $input->SetAttach($productinfo['goodsName']);
  $trade_no = WxPayConfig::MCHID.date("YmdHis").rand(100,999);
  $input->SetOut_trade_no($trade_no);
  $input->SetTotal_fee($goodsPriceTotal*100);
  //$input->SetTotal_fee(1);
  $input->SetTime_start(date("YmdHis"));
  $input->SetTime_expire(date("YmdHis", time() + 600));
  $input->SetGoods_tag("test");
  $input->SetNotify_url($mobile_url);
  $input->SetTrade_type("JSAPI");
  $input->SetOpenid($openId);
  $order = WxPayApi::unifiedOrder($input);
  echo '<font color="#f00"><b>统一下单支付单信息</b></font><br/>';
  printf_info($order);
  $jsApiParameters = $tools->GetJsApiParameters($order);

  //获取共享收货地址js函数参数
  $editAddress = $tools->GetEditAddressParameters();



  include $this->template('dopay');
 */
?>




