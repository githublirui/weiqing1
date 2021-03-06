<?php

global $_W, $_GPC;

require_once WXZ_PANORAMA . '/source/Project.class.php';
require_once WXZ_PANORAMA . '/source/Scene.class.php';

$pid = $_GPC['pid'];
$project_info = Project::getById($pid);

if (!$project_info) {
    message('项目不存在', $this->createWebUrl('activity_list'));
}

$project_config_info = Project::getConfigById($pid);
$effects = Scene::$effects;

//获取皮肤列表
$skinPath = WXZ_PANORAMA . '/template/mobile/scene/skin';
$skins = scandir($skinPath);
unset($skins[0], $skins[1]);

load()->web('tpl');
if (checksubmit()) {
    $data = array(
        'skin' => (string) $_GPC['skin'], //皮肤
        'effect' => (string) $_GPC['effect'], //特效
        'sound' => $_GPC['sound'], //背景音乐
        'treasure' => $_GPC['treasure'], //宝藏图片
        'treasure_num' => (int) $_GPC['treasure_num'], //宝藏数量
        'autorotate' => (int) $_GPC['autorotate'], //自动旋转
        'update_at' => time(),
    );
    
    $config['bottombanner'] = (int)$_GPC['bottombanner'];//是否展示底部banner
    $data['config'] = serialize($config);
            
    if (Project::updateConfig($pid, $data)) {
        message('更新成功', $this->createWebUrl('project_list'));
    } else {
        message('更新失败', $this->createWebUrl('project_config', array('pid' => $pid)));
    }
}
include $this->template('web/project_config');
?>
