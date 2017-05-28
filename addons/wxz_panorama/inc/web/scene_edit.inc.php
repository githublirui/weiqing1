<?php

require_once WXZ_PANORAMA . '/function/global.func.php';
global $_W, $_GPC;

$id = $_GPC['id'];
$scene_info_sql = "SELECT * FROM " . tablename('wxz_panorama_scene') . " WHERE id={$id}";
$scene_info = pdo_fetch($scene_info_sql);
if (!$scene_info) {
    message('场景不存在', $this->createWebUrl('scene_list'));
}
load()->web('tpl');
if (checksubmit()) {
    require_once WXZ_PANORAMA . '/source/UtilsFile.class.php';
    //字段验证, 并获得正确的数据$data
    $data = array(
        'name' => trim($_GPC['name']),
        'create_time' => time(),
    );

    $img_columns = array('front', 'back', 'up', 'down', 'left', 'right', 'treasures');

    foreach ($img_columns as $img_column) {
        if ($scene_info[$img_column] != $_GPC[$img_column]) {
            $data[$img_column] = $_GPC[$img_column];
        }
    }

    if (pdo_update('wxz_panorama_scene', $data, array('id' => $id))) {
        setting_load('remote');

        //目录处理
        $modulePath = '../addons/' . $_GPC['m'] . '/';
        $attachdir = IA_ROOT . '/' . $_W['config']['upload']['attachdir'] . '/';

        $id = $scene_info['id'];
        $demo_path = "$modulePath/template/scence_demo";
        $scene_base_path = "$modulePath/template/mobile/scene/{$_W['uniacid']}";
        $scene_path = "$scene_base_path/vrpano{$id}";
        if (!file_exists($scene_base_path)) {
            mkdir($scene_base_path, 0777, true);
        }

        $scene_img_path = "$modulePath/template/mobile/scene/{$_W['uniacid']}/vrpano{$id}/images";
        $sence_config_path = "$modulePath/template/mobile/scene/{$_W['uniacid']}/vrpano{$id}/krpano.php";
        $sence_index_path = "$modulePath/template/mobile/scene/{$_W['uniacid']}/vrpano{$id}/index.html";

        if (trim($_GPC['name']) != $scene_info['name']) {
            $sence_config_content = file_get_contents($sence_config_path);
            $sence_config_content = preg_replace('/(scenetitle\=)"(.*?)"/i', '${1}"' . $_GPC['name'] . '"${3}', $sence_config_content);
            file_put_contents($sence_config_path, $sence_config_content);

            $sence_index_content = file_get_contents($sence_index_path);
            $sence_index_content = preg_replace('/(\<title\>)(.*?)(\<\/title\>)/i', '${1}' . $_GPC['name'] . '${3}', $sence_index_content);
            file_put_contents($sence_index_path, $sence_index_content);
        }

        require_once WXZ_PANORAMA . '/source/UtilsImage.class.php';

        if (!empty($_W['setting']['remote']['type'])) {
            $url = $_W['setting']['remote']['qiniu']['url'];
            //远程附件处理
            foreach ($img_columns as $img_column) {
                if (isset($data[$img_column]) && $img_column == 'treasures') {
                    sence_img_process_remote($sence_config_path, $url . '/' . $data['front'], 'front');
                }
            }
            if (isset($data['front'])) {
                UtilsImage::square_crop($attachdir . $data['front'], $scene_img_path . "/thumb.jpg", '188');
            }
        } else {
            //本地图片处理
            foreach ($img_columns as $img_column) {
                if (isset($data[$img_column]) && $img_column == 'treasures') {
                    sence_img_process($attachdir . $data[$img_column], $scene_img_path, $img_column);
                }
            }
            if (isset($data['front'])) {
                UtilsImage::square_crop($attachdir . $data['front'], $scene_img_path . "/thumb.jpg", '188');
            }
        }


        message('更新成功', $this->createWebUrl('scene_list'));
    } else {
        message('更新失败', $this->createWebUrl('scene_add'));
    }
}
include $this->template('web/scene_edit');
?>
