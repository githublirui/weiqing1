<?php

/**
 * 设置管理员
 */
global $_W;
$_W['module_setting'] = $this->module['config'];
require_once WXZ_PAYMENT . "/lib/wxpay/example/WxPay.JsApiPay.php";
$tools = new JsApiPay();
$openId = $tools->GetOpenid();
if (!$openId) {
    message('获取opeid错误');
}

require_once WXZ_PAYMENT . '/source/Fans.class.php';
$f = new Fans();
$fans = $f->getOne($openId, true);
if ($fans) {
    pdo_update('wxz_payment_fans', array('is_admin' => 1), array('uid' => $fans['uid']));
} else {
    $insertData = array(
        'uniacid' => $_W['uniacid'],
        'openid' => $openId,
        'is_admin' => 1,
        'created' => time(),
    );
    pdo_insert('wxz_payment_fans', $insertData);
}

message('设置成功');
?>
