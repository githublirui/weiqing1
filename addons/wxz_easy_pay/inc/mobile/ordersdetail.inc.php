<?php

global $_GPC, $_W;
checkauth();
if ($_W['fans']) {
    
} else {
    message('用户授权错误', $this->createMobileUrl('my'));
}


if (empty($_GPC['oid'])) {
    message('订单不存在', $this->createMobileUrl('my'));
}

$oid = (int) $_GPC['oid'];
$getcode = (string) $_GPC['code'];

$orderinfo = pdo_get('hangyi_order', array('id' => $oid, 'sell_openid' => $_W['fans']['openid']));
if ($orderinfo) {

    $key = "wxz_easy_pay_{$_GPC['oid']}";
    $auth = 123456;
    require_once WXZ_EASY_PAY . '/function/global.func.php';
    $code = authcode($auth, '', $key);
    $shareUrl = $this->createMobileUrl('ordersdetail', array('oid' => $oid, 'code' => $code));
    $shareUrl = $_W['siteroot'] . 'app' . ltrim($shareUrl, '.');

    if ($orderinfo['sell_id'] != $_W['fans']['uid']) {
        //检测权限,分销商发货
        if (!$code) {
            message('无权限查看', $this->createMobileUrl('my'));
        }

        $decode = authcode($getcode, 'DECODE', $key);
        if ($decode != $auth) {
            message('无权限查看1', $this->createMobileUrl('my'));
        }
    }

    //买家信息
    if ($orderinfo['buy_id']) {
        $buyInfo = pdo_get('hangyi_user', array('uid' => $orderinfo['buy_id']));
    }
    if ($orderinfo['sell_id']) {
        $sellInfo = pdo_get('hangyi_user', array('uid' => $orderinfo['sell_id']));
    }

    $product_info = pdo_get('hangyi_product', array('id' => $orderinfo['pid']));

    $productIds = explode(',', $orderinfo['pids']);
    $goodnums = explode(',', $orderinfo['goodsNums']);
    $attrs = explode(',', $orderinfo['attrs']);

    foreach ($productIds as $productId) {
        $product_infos[] = pdo_get('hangyi_product', array('id' => $productId));
    }
} else {
    message('订单不存在', $this->createMobileUrl('my'));
}



include $this->template('orderdetail');
?>




