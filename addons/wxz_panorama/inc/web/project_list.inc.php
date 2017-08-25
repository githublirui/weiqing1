<?php

/*
 * 项目
 */

global $_W, $_GPC;
$aid = intval($_GPC['aid']);

$pindex = intval($_GPC['page']);
$pindex = max($pindex, 1);
$psize = 15;

$start = ($pindex - 1) * $psize;
$condition = "`uniacid`=:uniacid";

if ($aid) {
    $condition .= " AND aid={$aid}";
}

require_once WXZ_PANORAMA . '/source/Activity.class.php';
$activitys = Activity::getAll('id,name');

$pars = array();
$pars[':uniacid'] = $_W['uniacid'];

$sql = "SELECT count(*) as num FROM " . tablename('wxz_panorama_project') . " WHERE {$condition}";
$total = pdo_fetchcolumn($sql, $pars);

$sql = "SELECT * FROM " . tablename('wxz_panorama_project') . " WHERE {$condition} ORDER BY `sort_order` desc limit $start , $psize";

$list = pdo_fetchall($sql, $pars);
$pager = pagination($total, $pindex, $psize);

include $this->template('web/project_list');
?>
