<?php

global $_W, $_GPC;

$pindex = intval($_GPC['page']);
$pindex = max($pindex, 1);
$psize = 15;

$aid = intval($_GPC['aid']);
require_once WXZ_PANORAMA . '/source/Activity.class.php';
$activitys = Activity::getAll('id,name');

$start = ($pindex - 1) * $psize;
$condition = '`uniacid`=:uniacid';

if ($aid) {
    $condition .= " AND aid={$aid}";
}

$pars = array();
$pars[':uniacid'] = $_W['uniacid'];

$sql = "SELECT count(*) as num FROM " . tablename('wxz_panorama_award') . " WHERE {$condition}";
$total = pdo_fetchcolumn($sql, $pars);

$sql = "SELECT * FROM " . tablename('wxz_panorama_award') . " WHERE {$condition} ORDER BY `id` DESC limit $start , $psize";
$list = pdo_fetchall($sql, $pars);

foreach ($list as $k => $row) {
    $activityInfo = Activity::getById($row['aid'], 'id,name');
    $list[$k]['activity_name'] = $activityInfo['name'];
}

$pager = pagination($total, $pindex, $psize);

include $this->template('web/award_list');
?>
