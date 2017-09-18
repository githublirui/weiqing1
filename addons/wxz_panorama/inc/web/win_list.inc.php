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

$start = ($pindex - 1) * $psize;
$condition = '`uniacid`=:uniacid';
$pars = array();
$pars[':uniacid'] = $_W['uniacid'];

if ($aid) {
    $condition .= " AND aid={$aid}";
}

$sql = "SELECT count(*) as num FROM " . tablename('wxz_panorama_win') . " WHERE {$condition}";
$total = pdo_fetchcolumn($sql, $pars);

$sql = "SELECT * FROM " . tablename('wxz_panorama_win') . " WHERE {$condition} ORDER BY `fans_id` DESC limit $start , $psize";
$list = pdo_fetchall($sql, $pars);
foreach ($list as $k => $row) {
    $sql = "SELECT * FROM " . tablename('wxz_panorama_fans') . " WHERE uid={$row['fans_id']}";
    $fans = pdo_fetch($sql);
    $list[$k]['openid'] = $fans['openid'];
    $list[$k]['nickname'] = $fans['nickname'];
    $list[$k]['username'] = $fans['username'];
    $list[$k]['cellphone'] = $fans['cellphone'];
    $list[$k]['share_num'] = $fans['share_num'];
}
$pager = pagination($total, $pindex, $psize);

include $this->template('win_list');
?>
