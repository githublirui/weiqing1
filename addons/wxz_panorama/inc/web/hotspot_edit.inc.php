<?php

/**
 * 编辑热点
 */
global $_W, $_GPC;
require_once WXZ_PANORAMA . '/source/Scene.class.php';
require_once WXZ_PANORAMA . '/source/Project.class.php';
require_once WXZ_PANORAMA . '/source/Hotspot.class.php';

$id = intval($_GPC['id']);

$hotspotInfo = Hotspot::getById($id);
$projects = Project::getAll('id,name');

if (!$hotspotInfo) {
    message('热点不存在', $this->createWebUrl('project_list'));
}

$types = Hotspot::$types;
$actions = Hotspot::$actions;
$sceneInfo = Scene::getById($hotspotInfo['scene_id'], 'id,name,project_id');
$scenes = Scene::getScenesByProId($sceneInfo['project_id'], 'id,name');
$sid = $sceneInfo['id'];
//获取所有场景
if (checksubmit()) {
    $hotspotText = array(
        'openshowspotname' => (int) $_GPC['openshowspotname'],
        'spoth' => (string) $_GPC['spoth'],
        'spotv' => (string) $_GPC['spotv'],
    ); //热点配置，
    $data = array(
        'type' => (int) $_GPC['type'],
        'name' => (string) $_GPC['name'],
        'img' => (string) $_GPC['img'],
        'action' => (int) $_GPC['action'],
        'update_at' => time(),
    );
    switch ($data['action']) {
        case 1:
            $hotspotText['target_project'] = (int) $_GPC['target_project'];
            $hotspotText['target_scene'] = (int) $_GPC['target_scene'];
            $hotspotText['target_spoth'] = (string) $_GPC['target_spoth'];
            $hotspotText['target_spotv'] = (string) $_GPC['target_spotv'];
            $hotspotText['screenchange'] = (int) $_GPC['screenchange'];
            break;
        case 2:
            $hotspotText['showpic'] = (string) $_GPC['showpic'];
            $hotspotText['showpicbordercolor'] = (string) $_GPC['showpicbordercolor'];
            $hotspotText['showpicborderalpha'] = (string) $_GPC['showpicborderalpha'];
            $hotspotText['showpicborderwidth'] = (string) $_GPC['showpicborderwidth'];
            $hotspotText['showpictype'] = (int) $_GPC['showpictype'];
        case 3:
            $hotspotText['httplink'] = (string) $_GPC['httplink'];
            break;
    }

    $hotspotText['openinfo'] = (int) $_GPC['openinfo']; //热点文字
    $hotspotText['infowidth'] = (string) $_GPC['infowidth'];
    $hotspotText['textinfo'] = (string) $_GPC['textinfo'];
    $hotspotText['devicetype'] = (string) $_GPC['devicetype']; //支持设备
  
    $data['config'] = serialize($hotspotText);
    if (pdo_update('wxz_panorama_scene_hotspot', $data, array('id' => $id))) {
        message('修改成功', $this->createWebUrl('hotspot_list', array('sid' => $sid)));
    } else {
        message('修改失败', $this->createWebUrl('hotspot_edit', array('sid' => $sid)));
    }
}

load()->web('tpl');
include $this->template('web/hotspot_edit');
?>
