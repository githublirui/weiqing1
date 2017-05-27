<?php

global $_W, $_GPC;
require_once WXZ_PANORAMA . '/function/global.func.php';

if (checksubmit()) {
    //字段验证, 并获得正确的数据$dat
    $data = array(
        'uniacid' => $_W['uniacid'],
        'name' => $_GPC['name'],
        'front' => $_GPC['front'],
        'back' => $_GPC['back'],
        'up' => $_GPC['up'],
        'down' => $_GPC['down'],
        'left' => $_GPC['left'],
        'right' => $_GPC['right'],
        'create_time' => time(),
    );
    if (pdo_insert('wxz_panorama_scene', $data)) {
        //目录处理
        $modulePath = '../addons/' . $_GPC['m'] . '/';
        $attachdir = IA_ROOT . '/' . $_W['config']['upload']['attachdir'] . '/';
        require_once WXZ_PANORAMA . '/source/UtilsFile.class.php';
        $id = pdo_insertid();
        $demo_path = "$modulePath/template/scence_demo";
        $scene_base_path = "$modulePath/template/mobile/scene/{$_W['uniacid']}";
        $scene_path = "$scene_base_path/vrpano{$id}";
        $scene_img_path = "$modulePath/template/mobile/scene/{$_W['uniacid']}/vrpano{$id}/images";
        $sence_config_path = "$modulePath/template/mobile/scene/{$_W['uniacid']}/vrpano{$id}/krpano.php";
        $sence_index_path = "$modulePath/template/mobile/scene/{$_W['uniacid']}/vrpano{$id}/index.html";

        if (!file_exists($scene_base_path)) {
            mkdir($scene_base_path, 0777, true);
        }

        if (file_exists($scene_path)) {
            UtilsFile::deleteDirectory($scene_path);
        }
        copy_dir($demo_path, $scene_path);
        $sence_confi_content = file_get_contents($sence_config_path);
        $sence_confi_content = str_replace('{$scenetitle}', $_GPC['name'], $sence_confi_content);
        file_put_contents($sence_config_path, $sence_confi_content);

        $sence_index_content = file_get_contents($sence_index_path);
        $sence_index_content = str_replace('{$scenetitle}', $_GPC['name'], $sence_index_content);
        file_put_contents($sence_index_path, $sence_index_content);
        //图片处理
        require_once WXZ_PANORAMA . '/source/UtilsImage.class.php';
        sence_img_process($attachdir . $data['front'], $scene_img_path, 'front');
        sence_img_process($attachdir . $data['back'], $scene_img_path, 'back');
        sence_img_process($attachdir . $data['up'], $scene_img_path, 'up');
        sence_img_process($attachdir . $data['down'], $scene_img_path, 'down');
        sence_img_process($attachdir . $data['left'], $scene_img_path, 'left');
        sence_img_process($attachdir . $data['right'], $scene_img_path, 'right');
        UtilsImage::square_crop($attachdir . $data['front'], $scene_img_path . "/thumb.jpg", '188');
        message('添加成功', $this->createWebUrl('scene_list'));
    } else {
        message('添加失败', $this->createWebUrl('scene_add'));
    }
}

load()->web('tpl');
include $this->template('web/scene_add');
?>
