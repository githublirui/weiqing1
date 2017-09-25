<?php

/**
 * 下载订单
 */
require_once WXZ_EASY_PAY . '/lib/wxzCsv.class.php';

global $_GPC, $_W;
load()->model('mc');
$fan = mc_fansinfo($_W['openid']);
if (!empty($fan) && !empty($fan['openid'])) {
    $userinfo = $fan;
} else {
    $userinfo = mc_oauth_userinfo();
}

$condition = "sell_id={$userinfo['uid']} AND uniacid={$_W['uniacid']}";

if ($_GPC['order_status']) {
    $condition .= " AND order_status!=2";
}

$orders = pdo_getall('hangyi_order', $condition);
$header = array(
    '订单ID', '订单状态', '订单金额', '创建时间', '支付时间', '发货时间', '收货时间', '卖家手机号', '卖家微信'
);

$csv = new WxzCsv();
$csv->createRow($header);
$sellInfos = array();

foreach ($orders as $order) {
    if (!$sellInfos[$order['sell_id']]) {
        $sellInfos[$order['sell_id']] = pdo_get('hangyi_user', array('uid' => $order['sell_id']));
    }

    $sellInfo = $sellInfos[$order['sell_id']];
    
    $payTime = $sendTime = $getTime = '';

    //订单状态
    if ($order['pay_status'] != 2) {
        $status = '未付款';
    } else {
        if ($order['order_status'] != 2) {
            $status = '未发货';
        } else {
            $status = '已发货';
        }
    }

    $addTime = date('Y-m-d H:i:s', $order['add_time']);
    if ($order['pay_time']) {
        $payTime = date('Y-m-d H:i:s', $order['pay_time']);
    }
    if ($order['send_time']) {
        $sendTime = date('Y-m-d H:i:s', $order['send_time']);
    }
    if ($order['get_time']) {
        $getTime = date('Y-m-d H:i:s', $order['get_time']);
    }

    $data = array(
        $order['id'], $status, $order['goodsPriceTotalReal'], $addTime, $payTime, $sendTime, $getTime, $sellInfo['tel'], $sellInfo['weixin']
    );
    
    $csv->createRow($data);
}

$csv->download(date('ymdHi').'订单.csv');
?>
