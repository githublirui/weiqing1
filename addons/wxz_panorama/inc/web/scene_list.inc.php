<?php

global $_W, $_GPC;

$pid = intval($_GPC['pid']);
if (!$pid) {
    message('请选择一个项目', $this->createWebUrl('activity_list'));
}

$pindex = intval($_GPC['page']);
$pindex = max($pindex, 1);
$psize = 15;

$start = ($pindex - 1) * $psize;
$condition = "`uniacid`=:uniacid AND project_id={$pid}";
$pars = array();
$pars[':uniacid'] = $_W['uniacid'];

$sql = "SELECT count(*) as num FROM " . tablename('wxz_panorama_scene') . " WHERE {$condition}";
$total = pdo_fetchcolumn($sql, $pars);

$sql = "SELECT * FROM " . tablename('wxz_panorama_scene') . " WHERE {$condition} ORDER BY `id` DESC limit $start , $psize";
$list = pdo_fetchall($sql, $pars);
$pager = pagination($total, $pindex, $psize);

include $this->template('web/scene_list');
?>
