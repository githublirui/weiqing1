<?php

/*
 * 场景页面 
 * 
 */
require_once WXZ_PANORAMA . '/function/global.func.php';
require_once WXZ_PANORAMA . '/source/Scene.class.php';
require_once WXZ_PANORAMA . '/source/Project.class.php';

global $_W, $_GPC;
$modulePath = '../addons/' . $_GPC['m'] . '/';

$pid = $_GPC['pid']; //默认第一个项目
if (!$pid) {
    message('场景id错误', $this->createWebUrl('activity_list'));
}
$projectInfo = Project::getById($pid, 'id');
if (!$projectInfo) {
    message('场景不存在', $this->createWebUrl('activity_list'));
}
$pid = $projectInfo['id'];

include $this->template('web/scene_preview');
?>
