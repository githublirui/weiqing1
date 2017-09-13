<?php

/**
 * 添加热点
 */
global $_W, $_GPC;
require_once WXZ_PANORAMA . '/source/Scene.class.php';
require_once WXZ_PANORAMA . '/source/Project.class.php';
require_once WXZ_PANORAMA . '/source/Hotspot.class.php';

$sid = intval($_GPC['sid']);
if (!$sid) {
    message('请选择一个项目场景', $this->createWebUrl('activity_list'));
}

$types = Hotspot::$types;
$actions = Hotspot::$actions;
$sceneInfo = Scene::getById($sid, 'id,name,project_id');
$scenes = Scene::getScenesByProId($sceneInfo['project_id'], 'id,name');
$projects = Project::getAll('id,name');

//获取所有场景
if (checksubmit()) {
    $hotspotText = array(
        'openshowspotname' => (int) $_GPC['openshowspotname'],
        'spoth' => (string) $_GPC['spoth'],
        'spotv' => (string) $_GPC['spotv'],
    ); //热点配置，
    $data = array(
        'uniacid' => $_W['uniacid'],
        'type' => (int) $_GPC['type'],
        'project_id' => $sceneInfo['project_id'],
        'scene_id' => $sid,
        'name' => (string) $_GPC['name'],
        'img' => (string) $_GPC['img'],
        'action' => (int) $_GPC['action'],
        'create_at' => time(),
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
    $hotspotText['textinfo'] = str_replace(' ', '', $hotspotText['textinfo']);
    $hotspotText['openinfo'] = (int) $hotspotText['openinfo'];
    $hotspotText['infowidth'] = (int) $hotspotText['infowidth'];
    $hotspotText['textinfo'] = (string) $hotspotText['textinfo'];
    $hotspotText['devicetype'] = (string) $hotspotText['devicetype']; //支持设备

    $data['config'] = serialize($hotspotText);
    if (pdo_insert('wxz_panorama_scene_hotspot', $data)) {
        message('添加成功', $this->createWebUrl('hotspot_list', array('sid' => $sid)));
    } else {
        message('添加失败', $this->createWebUrl('hotspot_add', array('sid' => $sid)));
    }
}

load()->web('tpl');
include $this->template('web/hotspot_add');
?>
