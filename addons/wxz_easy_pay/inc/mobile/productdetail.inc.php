<?php

//print_R($_FILES);
global $_GPC, $_W;
load()->model('mc');
$fan = mc_fansinfo($_W['openid']);
if (!empty($fan) && !empty($fan['openid'])) {
    $userinfo = $fan;
} else {
    $userinfo = mc_oauth_userinfo();
}

$pid = (int) $_GPC['pid'];
$uniacid = $_W['uniacid'];
$setting_font = pdo_get('hangyi_add_font', array('uniacid' => $uniacid));

$products = pdo_getall('hangyi_product', array('batch_id' => $pid));

$sell_info = pdo_get('hangyi_user', array('uid' => $product['uid']));
$buy_info = pdo_get('hangyi_user', array('uid' => $userinfo['uid']));


if ($_GPC['ajax']) {
    if (empty($product)) {
        exit();
    }
    $buyer_name = $_GPC['buyer_name'];
    $cell = $_GPC['cell'];
    $weixin = $_GPC['weixin'];
    $address = $_GPC['address'];
    $postscript = $_GPC['postscript'];
    $goodsNum = (int) $_GPC['goodsNum'];
    $sell_openid = $product['openid'];
    $sell_id = $product['uid'];
    if ($goodsNum < 1) {
        $goodsNum = 1;
    }
    $goodsPriceTotalReal = $product['goodsPrice'] * $goodsNum;
    if ($product['postage'] && $product['goodsPostNum'] >= $goodsNum) {
        $goodsPriceTotalReal = $goodsPriceTotalReal + $product['postage'];
    }

    $goodsPriceTotal = $_GPC['goodsPriceTotal'];
    //生成订单
    $dat['set'] = array(
        'buy_openid' => $userinfo['openid'],
        'add_time' => time(),
        'price' => $product['goodsPrice'],
        'sell_id' => $sell_id,
        'buy_id' => $userinfo['uid'],
        'buy_nick' => $buyer_name,
        'cell' => $cell,
        'weixin' => $weixin,
        'address' => $address,
        'goodsName' => $product['goodsName'],
        'goodsNum' => $goodsNum,
        'pay_status' => 1,
        'order_status' => 1,
        'pid' => $pid,
        'postscript' => $postscript,
        'sell_openid' => $sell_openid,
        'buy_nickname' => $userinfo['nickname'],
        'sell_nickname' => $sell_info['nickname'],
        'goodsPriceTotalReal' => $goodsPriceTotalReal,
        'goodsPriceTotal' => $goodsPriceTotalReal
    ); //print_R($dat['set']);die;
    $insertorder = pdo_insert('hangyi_order', $dat['set']);
    $oid = pdo_insertid();
    //更新用户地址

    $user_data = array(
        'realname' => $buyer_name,
        'tel' => $cell,
        'weixin' => $weixin,
        'order_address' => $address,
    );
    $result = pdo_update('hangyi_user', $user_data, array('uid' => $buy_info['uid']));

    echo $oid;
    exit();
}

if ($products) {
    $realname = $buy_info['realname'];
    $tel = $buy_info['tel'];
    $order_address = $buy_info['order_address'];
    $weixin = $buy_info['weixin'];

    include $this->template('product_pay');
}
?>


