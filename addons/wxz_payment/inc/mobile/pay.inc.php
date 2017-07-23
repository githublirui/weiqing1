<?php

load()->classs('weixin.account');

/**
 * 支付
 */
global $_W, $_GPC;
$modulePublic = '../addons/' . $_GPC['m'] . '/static/';

$_W['module_setting'] = $this->module['config'];
require_once WXZ_PAYMENT . "/lib/wxpay/example/WxPay.JsApiPay.php";
require_once WXZ_PAYMENT . '/source/Fans.class.php';

$shopId = $_GPC['shop_id'];
if (!$shopId) {
    message('店面参数错误');
}
$shop_info_sql = "SELECT * FROM " . tablename('wxz_payment_shop') . " WHERE id={$shopId}";
$shop_info = pdo_fetch($shop_info_sql);
if (!$shop_info) {
    message('店面不存在');
}
if (!$shop_info['openid']) {
    message('店面未认证');
}
$tools = new JsApiPay();
$openId = $tools->GetOpenid();
if (!$openId) {
    message('获取opeid错误');
}

$f = new Fans();
$fans = $f->getOne($openId, true);

if (!$fans) {
    $insertData = array(
        'uniacid' => $_W['uniacid'],
        'openid' => $openId,
        'created' => time(),
    );
    pdo_insert('wxz_payment_fans', $insertData);
}

include $this->template('pay');
?>
