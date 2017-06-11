<?php

/*
 * 热点列表
 */
require_once WXZ_PANORAMA . '/source/Hotspot.class.php';
global $_W, $_GPC;

$pindex = intval($_GPC['page']);
$pindex = max($pindex, 1);
$psize = 15;

$sid = intval($_GPC['sid']);
if (!$sid) {
    message('请选择一个项目场景', $this->createWebUrl('project_list'));
}

$start = ($pindex - 1) * $psize;
$condition = "`uniacid`=:uniacid AND scene_id={$sid}";
$pars = array();
$pars[':uniacid'] = $_W['uniacid'];

$sql = "SELECT count(*) as num FROM " . tablename('wxz_panorama_scene_hotspot') . " WHERE {$condition}";
$total = pdo_fetchcolumn($sql, $pars);

$sql = "SELECT * FROM " . tablename('wxz_panorama_scene_hotspot') . " WHERE {$condition} ORDER BY `id` desc limit $start , $psize";

$list = pdo_fetchall($sql, $pars);
$pager = pagination($total, $pindex, $psize);

include $this->template('web/hotspot_list');
?>
