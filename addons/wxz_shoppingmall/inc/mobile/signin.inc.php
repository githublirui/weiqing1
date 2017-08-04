<?php

/**
 * 签到功能
 */
global $_W, $_GPC;
include dirname(__FILE__) . '/permission.php';

if (!$user['mobile']) {
    header('Location: ' . $this->createMobileUrl('reg'));
    exit;
}
$modulePublic = '../addons/' . $_GPC['m'] . '/static/';

$firstday = date('Y-m-d 00:00:00', mktime(0, 0, 0, date('m'), 1));
$lastda = date('Y-m-d 23:59:59', mktime(0, 0, 0, date('m') + 1, 1) - 1);
$firstdayTime = strtotime($firstday);
$lastdaTime = strtotime($lastda);

//获取签到次数
$sql = "select count(1) num,sum(num) total_credit from " . tablename('wxz_shoppingmall_credit_log') . " where event_type=11 AND create_at>={$firstdayTime} AND create_at<={$lastdaTime}";

$totalSign = pdo_fetch($sql);
include $this->template('signin');
?>
