<?php

/*
 * 
 * 首页
 */
global $_W, $_GPC;
$modulePath = '../addons/' . $_GPC['m'] . '/static/';

//获取图片域名
setting_load('remote');
if ($_W['setting']['remote']['type']) {
    $attach_url = $_W['attachurl_remote'];
} else {
    $attach_url = $_W['siteroot'] . $_W['config']['upload']['attachdir'];
}

$user = $this->auth();

//获取视频列表
$condition = "`uniacid`={$_W['uniacid']} AND page='index'";
$sql = "SELECT * FROM " . tablename('wxz_openeye_page') . " WHERE {$condition} ORDER BY `order` DESC";
$list = pdo_fetchall($sql, $pars);
$result = array();

foreach ($list as $row) {
    $result[$row['page_type']][] = $row; //列表类型
}

$list1Num = 2;
$list2Num = 2;
//截取list
$result['list1'] = array_slice($result['list'], 0, $list1Num); //显示5个
//剩余列表
$result['list2'] = array_slice($result['list'], $list1Num, $list2Num); //剩余列表
$result['list3'] = array_slice($result['list'], $list1Num + $list2Num, count($result['list'])); //剩余列表

include $this->template('index');
?>
