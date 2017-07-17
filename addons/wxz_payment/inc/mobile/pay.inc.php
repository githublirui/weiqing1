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
