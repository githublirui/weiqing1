<?php

global $_W, $_GPC;

$filters = array();
$filters['uniacid'] = $_W['uniacid'];
$filters['nickname'] = $_GPC['nickname'];

$pindex = intval($_GPC['page']);
$pindex = max($pindex, 1);
$psize = 15;

//获取所有活动
require_once WXZ_PANORAMA . '/source/Activity.class.php';
$activitys = Activity::getAll('id,name');

$aid = intval($_GPC['aid']);
$mobile = intval($_GPC['mobile']);

$start = ($pindex - 1) * $psize;
$condition = '`uniacid`=:uniacid';
$pars = array();
$pars[':uniacid'] = $_W['uniacid'];

if ($aid) {
    $condition .= " AND aid={$aid}";
}

if ($mobile) {
    $condition .= " AND cellphone='{$mobile}'";
}

$sql = "SELECT count(*) as num FROM " . tablename('wxz_panorama_fans') . " WHERE {$condition}";
$total = pdo_fetchcolumn($sql, $pars);

$sql = "SELECT * FROM " . tablename('wxz_panorama_fans') . " WHERE {$condition} ORDER BY `uid` DESC limit $start , $psize";
$list = pdo_fetchall($sql, $pars);
$pager = pagination($total, $pindex, $psize);

include $this->template('fans_list');
?>
