<?php

require_once WXZ_PANORAMA . '/function/global.func.php';
require_once WXZ_PANORAMA . '/source/Scene.class.php';

global $_W, $_GPC;

$id = $_GPC['id'];
$img_columns = array('front', 'back', 'up', 'down', 'left', 'right'); //六张图片
$scene_info_sql = "SELECT * FROM " . tablename('wxz_panorama_scene') . " WHERE id={$id}";
$scene_info = pdo_fetch($scene_info_sql);
if (!$scene_info) {
    message('场景不存在', $this->createWebUrl('scene_list'));
}

//格式化存储字段
foreach ($scene_info as $column => $value) {
    if (in_array($column, $img_columns) && $value) {
        $scene_info[$column] = unserialize($value);
    }
}


load()->web('tpl');
if (checksubmit()) {
    require_once WXZ_PANORAMA . '/source/UtilsFile.class.php';
    //字段验证, 并获得正确的数据$data
    $data = array(
        'name' => trim($_GPC['name']),
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
