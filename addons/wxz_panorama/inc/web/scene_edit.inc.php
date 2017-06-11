<?php

require_once WXZ_PANORAMA . '/function/global.func.php';
require_once WXZ_PANORAMA . '/source/Scene.class.php';

global $_W, $_GPC;

$id = $_GPC['id'];
$img_columns = array('front', 'back', 'up', 'down', 'left', 'right');

$scene_info = Scene::getById($id);
if (!$scene_info) {
    message('场景不存在', $this->createWebUrl('scene_list'));
}

load()->web('tpl');
if (checksubmit()) {
    require_once WXZ_PANORAMA . '/source/UtilsFile.class.php';
    //字段验证, 并获得正确的数据$data
    $data = array(
        'name' => trim($_GPC['name']),
        'preview' => (string) $_GPC['preview'], //预览图
        'thumb' => (string) $_GPC['thumb'], //缩略图
        'create_time' => time(),
    );

    foreach ($img_columns as $img_column) {
        if ($_GPC[$img_column] && $scene_info[$img_column]['pano'] != $_GPC[$img_column]) {
            $imgSrc = Scene::getSceneImageSrc($_GPC[$img_column]);
            $data[$img_column] = serialize($imgSrc);
        }
    }

    if (pdo_update('wxz_panorama_scene', $data, array('id' => $id))) {
        message('更新成功', $this->createWebUrl('scene_list', array('pid' => $scene_info['project_id'])));
    } else {
        message('更新失败', $this->createWebUrl('scene_edit', array('id' => $id)));
    }
}
include $this->template('web/scene_edit');
?>
