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
    $result = pdo_query("UPDATE " . tablename('hangyi_order') . " SET `order_status`='2'  WHERE `sell_id` = '" . $uid . "'  and `order_status`=1 ");
    if ($result) {
        echo "ok";
    }
    exit();
}

$today = date("Y-m-d");
$yestoday = date("Y-m-d", strtotime('-1 day'));
$sql = "SELECT count(*) as totalNum FROM " . tablename('hangyi_order') . " as o left join " . tablename('core_paylog') . "  as p on o.id=p.tid WHERE o.`sell_id`='" . $uid . "' and p.`status`=1 and FROM_UNIXTIME(`pay_time`,'%Y-%m-%d')='" . $yestoday . "' ";
$re_order_num = pdo_fetch($sql, $params = array());
$alltotal = (int) $re_order_num['totalNum'];

$sql = "SELECT count(*) as weifah FROM " . tablename('hangyi_order') . " as o left join " . tablename('core_paylog') . "  as p on o.id=p.tid  WHERE o.`sell_id`='" . $uid . "' and p.`status`=1 and o.`order_status`=1 ";
$weifahTotal = pdo_fetch($sql, $params = array());
$weifah = (int) $weifahTotal['weifah'];

$sql = "SELECT sum(goodsPriceTotalReal) as yestodayMoney FROM " . tablename('hangyi_order') . " as o left join " . tablename('core_paylog') . "  as p on o.id=p.tid WHERE o.`sell_id`='" . $uid . "' and p.`status`=1 and FROM_UNIXTIME(`pay_time`,'%Y-%m-%d')='" . $yestoday . "' ";
$re_order = pdo_fetch($sql, $params = array());
$yestodayMoney = (int) $re_order['yestodayMoney'];

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




