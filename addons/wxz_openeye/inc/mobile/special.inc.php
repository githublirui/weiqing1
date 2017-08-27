<?php

/*
 * 
 * 专题页面
 */
global $_W, $_GPC;
$modulePath = '../addons/' . $_GPC['m'] . '/static/';
$_W['module_config'] = $this->module['config'];

//获取图片域名
setting_load('remote');
if ($_W['setting']['remote']['type']) {
    $attach_url = $_W['attachurl_remote'];
} else {
    $attach_url = $_W['siteroot'] . $_W['config']['upload']['attachdir'];
}

$user = $this->auth();

$id = $_GPC['id']; //专题ID

$special_info_sql = "SELECT * FROM " . tablename('wxz_openeye_special') . " WHERE id={$id}";
$special_info = pdo_fetch($special_info_sql);

//获取图片域名
setting_load('remote');
if ($_W['setting']['remote']['type']) {
    $attach_url = $_W['attachurl_remote'];
} else {
    $attach_url = $_W['siteroot'] . $_W['config']['upload']['attachdir'];
}


//获取视频列表
$condition = "`uniacid`={$_W['uniacid']} AND special_id='{$id}'";
$sql = "SELECT * FROM " . tablename('wxz_openeye_special_page') . " WHERE {$condition} ORDER BY `id` DESC";
$list = pdo_fetchall($sql, $pars);

foreach ($list as $k => $row) {
    $page_info_sql = "SELECT * FROM " . tablename('wxz_openeye_page') . " WHERE id={$row['page_id']}";
    $special_info_sql = "SELECT * FROM " . tablename('wxz_openeye_special') . " WHERE id={$row['special_id']}";
    $row['page'] = pdo_fetch($page_info_sql);
    $list[$k] = $row;
}

include $this->template('special');
?>
