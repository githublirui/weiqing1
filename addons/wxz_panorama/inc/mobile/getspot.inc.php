<?php

/**
 * 抓取场景坐标
 */
global $_W, $_GPC;
$modulePath = '../addons/' . $_GPC['m'] . '/';

require_once WXZ_PANORAMA . '/source/Scene.class.php';

$id = (int) $_GPC['id'];

$data = $id . '|' . $_GPC['styleid'] . '|' . $_GPC['h'] . '|' . $_GPC['v'];


$xmlUrl = $this->createMobileUrl("getspotxml", array("data" => $data));
include $this->template('scene/getspot');
?>