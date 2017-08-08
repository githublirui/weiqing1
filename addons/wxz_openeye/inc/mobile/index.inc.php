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

include $this->template('index');
?>
