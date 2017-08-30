<?php

global $_W, $_GPC;

$pid = intval($_GPC['pid']);
if (!$pid) {
    message('请选择一个项目', $this->createWebUrl('activity_list'));
}

require_once WXZ_PANORAMA . '/function/global.func.php';
require_once (IA_ROOT . '/framework/library/qiniu/autoload.php'); //七牛
require_once WXZ_PANORAMA . '/source/Page.class.php';
require_once WXZ_PANORAMA . '/source/Scene.class.php';

if (checksubmit()) {
    //字段验证, 并获得正确的数据
    $data = array(
        'uniacid' => $_W['uniacid'],
        'project_id' => $pid,
        'name' => (string) $_GPC['name'],
        'preview' => (string) $_GPC['preview'], //预览图
        'thumb' => (string) $_GPC['thumb'], //缩略图
        'create_time' => time(),
    );

    $img_columns = array('front', 'back', 'up', 'down', 'left', 'right');

    foreach ($img_columns as $img_column) {
        $imgSrc = Scene::getSceneImageSrc($_GPC[$img_column]);
        $data[$img_column] = serialize($imgSrc);
    }

    if (pdo_insert('wxz_panorama_scene', $data)) {
        message('添加成功', $this->createWebUrl('scene_list', array('pid' => $pid)));
    } else {
        message('添加失败', $this->createWebUrl('scene_add', array('pid' => $pid)));
    }
}

load()->web('tpl');
include $this->template('web/scene_add');
?>
