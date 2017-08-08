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

//获取视频详情
$id = $_GPC['id'];
$condition = "`uniacid`={$_W['uniacid']} AND id='{$id}'";
$sql = "SELECT * FROM " . tablename('wxz_openeye_page') . " WHERE {$condition}";
$info = pdo_fetch($sql);

//随机取三个视频
$sql = "SELECT * FROM " . tablename('wxz_openeye_page') . " order by rand() limit 3";
$list = pdo_fetchall($sql);

include $this->template('detail');
?>
