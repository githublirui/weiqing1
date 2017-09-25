<?php

global $_GPC, $_W;
load()->model('mc');
$fan = mc_fansinfo($_W['openid']);
if (!empty($fan) && !empty($fan['openid'])) {
    $userinfo = $fan;
} else {
    $userinfo = mc_oauth_userinfo();
}

$uid = $userinfo['uid'];

if ($_GPC['ac'] == 'ajaxshipall' && $uid) {
    $condition = "sell_id={$uid} AND `order_status`=1 AND order_status!=2";
    $orderInfos = pdo_getall('hangyi_order', $condition);
    //发送发货消息
//    foreach($orderInfos as $orderInfo) {
//        $result = pdo_query("UPDATE " . tablename('hangyi_order') . " SET `order_status`='2'  WHERE id={$orderInfo['id']}");
//        //发送通知
//        if($result) {
//            
//        }
//    }
    echo "ok";
    exit;
}

$today = date("Y-m-d");
$todayTime = strtotime(date("Y-m-d"));
$yestodayTime = strtotime('-1 day');
$yestoday = date("Y-m-d", strtotime('-1 day'));

$sql = "SELECT id,goodsPriceTotalReal FROM " . tablename('hangyi_order') . "  where sell_id={$uid} AND pay_status=2 AND pay_time>='{$yestodayTime}' AND pay_time<'{$todayTime}' AND uniacid={$_W['uniacid']}";
$yestodayOrders = pdo_fetchall($sql, $params = array());

$alltotal = 0;
$yestodayMoney = 0;
foreach ($yestodayOrders as $yestodayOrder) {
    $alltotal++;
    $yestodayMoney += $yestodayOrder['goodsPriceTotalReal'] * 100;
}
$yestodayMoney = $yestodayMoney / 100;

$sql = "SELECT count(*) as weifah FROM " . tablename('hangyi_order') . "  where sell_id={$uid} AND pay_status=2 AND order_status!=2 AND uniacid={$_W['uniacid']}";
$weifahTotal = pdo_fetch($sql, $params = array());
$weifah = (int) $weifahTotal['weifah'];

//获取页面配置
require_once WXZ_EASY_PAY . '/source/Page.class.php';
$pageInfo = Page::getPage(array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11));
//获取图片域名
setting_load('remote');
if ($_W['setting']['remote']['type']) {
    $attach_url = $_W['attachurl_remote'];
} else {
    $attach_url = $_W['siteroot'] . $_W['config']['upload']['attachdir'];
}

include $this->template('my_detail');
?>




