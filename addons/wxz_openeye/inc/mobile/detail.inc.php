<?php

/*
 * 
 * 首页
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

//获取视频详情
$id = $_GPC['id'];
$condition = "`uniacid`={$_W['uniacid']} AND id='{$id}'";
$sql = "SELECT * FROM " . tablename('wxz_openeye_page') . " WHERE {$condition}";
$info = pdo_fetch($sql);

$condition = "`uniacid`={$_W['uniacid']} AND page='detail'";
$sql = "SELECT * FROM " . tablename('wxz_openeye_page_position') . " WHERE {$condition} ORDER BY `order` DESC";
$list = pdo_fetchall($sql, $pars);
if (!$list) {
    //随机取三个视频
    $sql = "SELECT * FROM " . tablename('wxz_openeye_page') . " order by rand() limit 3";
    $list = pdo_fetchall($sql);
} else {
    foreach ($list as $k => $row) {
        $page_info_sql = "SELECT * FROM " . tablename('wxz_openeye_page') . " WHERE id={$row['page_id']}";
        $page_info = pdo_fetch($page_info_sql);
        $list[$k] = array_merge($page_info, $row);
    }
}

//获取所有评论
$condition = "uniacid={$_W['uniacid']} AND page_id={$id}";
$sql = "SELECT * FROM " . tablename('wxz_openeye_comment') . " WHERE {$condition} ORDER BY `id` DESC";
$comments = pdo_fetchall($sql);

foreach ($comments as $k => $row) {
    $sql = "SELECT * FROM " . tablename('wxz_openeye_fans') . " WHERE uid={$row['fans_id']}";
    $fansInfo = pdo_fetch($sql);
    $comments[$k]['fans_info'] = $fansInfo;
    $comments[$k]['date_format'] = date('Y-m-d H:i', $row['create_time']);
}
include $this->template('detail');
?>
