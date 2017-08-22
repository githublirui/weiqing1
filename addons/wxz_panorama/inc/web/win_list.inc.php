<?php

global $_W, $_GPC;

$filters = array();
$filters['uniacid'] = $_W['uniacid'];
$filters['nickname'] = $_GPC['nickname'];

$aid = intval($_GPC['aid']);

require_once WXZ_PANORAMA . '/source/Activity.class.php';
$activitys = Activity::getAll('id,name');

$pindex = intval($_GPC['page']);
$pindex = max($pindex, 1);
$psize = 15;

$start = ($pindex - 1) * $psize;
$condition = '`uniacid`=:uniacid';

if ($aid) {
    $condition .= " AND aid={$aid}";
}

$pars = array();
$pars[':uniacid'] = $_W['uniacid'];

$sql = "SELECT count(*) as num FROM " . tablename('wxz_panorama_win') . " WHERE {$condition}";
$total = pdo_fetchcolumn($sql, $pars);

$sql = "SELECT * FROM " . tablename('wxz_panorama_win') . " WHERE {$condition} ORDER BY `fans_id` DESC limit $start , $psize";
$list = pdo_fetchall($sql, $pars);
foreach ($list as &$row) {
    $sql = "SELECT * FROM " . tablename('wxz_panorama_fans') . " WHERE uid={$row['fans_id']}";
    $fans = pdo_fetch($sql);
    $activityInfo = Activity::getById($row['aid'], 'id,name');

    $row['openid'] = $fans['openid'];
    $row['nickname'] = $fans['nickname'];
    $row['username'] = $fans['username'];
    $row['cellphone'] = $fans['cellphone'];
    $row['share_num'] = $fans['share_num'];
    $row['activity_name'] = $activityInfo['name'];
}
$pager = pagination($total, $pindex, $psize);

include $this->template('win_list');
?>
