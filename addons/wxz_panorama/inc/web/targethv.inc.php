<?php

/**
 * 抓取目标场景坐标
 */
global $_W, $_GPC;
$modulePath = '../addons/' . $_GPC['m'] . '/';

require_once WXZ_PANORAMA . '/source/Scene.class.php';

$id = (int) $_GPC['id'];

$data = $id;


$xmlUrl = $this->createWebUrl("targethvxml", array("data" => $data));
include $this->template('web/targethv');
?>