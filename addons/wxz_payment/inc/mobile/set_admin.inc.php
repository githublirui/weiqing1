<?php

require_once WXZ_PAYMENT . '/source/Order.class.php';

$orderInfo = Order::getOrderByOrderNo('201707231933340801');
$shop_info_sql = "SELECT * FROM " . tablename('wxz_payment_shop') . " WHERE id={$orderInfo['shop_id']}";
$shop_info = pdo_fetch($shop_info_sql);

$upSql = " set total_money = total_money + '{$orderInfo['pay_money']}'";
$charges = 0; //手续费
if ($shop_info['charges_percent']) {
    $charges = (int) ($shop_info['charges_percent'] / 100 * $orderInfo['pay_money']);
    $charges = $charges < 1 ? 1 : $charges;
    $upSql .= ", charges = charges + $charges";
}

//给用户打款金额
$payMoney = $orderInfo['pay_money'] - $charges + $shop_info['balance'];
//小于一元记录到余额
if ($payMoney < 100) {
    $upSql .= ", balance = balance + {$orderInfo['pay_money']}";
}

//给用户企业打款
if ($payMoney >= 100) {
    //企业打款
}

$ret = pdo_query("update " . tablename('wxz_payment_shop') . " {$upSql} where id={$orderInfo['shop_id']}");

var_dump($orderInfo);
die;

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
    pdo_update('wxz_payment_fans', array('is_check' => 1), array('uid' => $fans['uid']));
} else {
    $insertData = array(
        'uniacid' => $_W['uniacid'],
        'openid' => $openId,
        'is_admin' => 1,
        'created' => time(),
    );
    pdo_insert('wxz_payment_fans', $insertData);
}

message('申请成功');
?>