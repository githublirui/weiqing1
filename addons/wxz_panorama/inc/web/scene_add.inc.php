<?php

global $_W, $_GPC;
require_once WXZ_PANORAMA . '/function/global.func.php';
require_once (IA_ROOT . '/framework/library/qiniu/autoload.php'); //七牛
require_once WXZ_PANORAMA . '/source/Page.class.php';

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
        'treasures' => $_GPC['treasures'],
        'create_time' => time(),
    );
    if (pdo_insert('wxz_panorama_scene', $data)) {
        setting_load('remote');

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

        //目录拷贝
        if (!file_exists($scene_base_path)) {
            mkdir($scene_base_path, 0777, true);
        }

        if (file_exists($scene_path)) {
            UtilsFile::deleteDirectory($scene_path);
        }
        copy_dir($demo_path, $scene_path);
        $sence_config_content = file_get_contents($sence_config_path);
        $sence_config_content = str_replace('{$scenetitle}', $_GPC['name'], $sence_config_content);
        file_put_contents($sence_config_path, $sence_config_content);

        $sence_index_content = file_get_contents($sence_index_path);
        $sence_index_content = str_replace('{$scenetitle}', $_GPC['name'], $sence_index_content);
        file_put_contents($sence_index_path, $sence_index_content);

        if ($_W['setting']['remote']['type'] == 3) {
            //七牛远程附件图片处理
            $url = $_W['setting']['remote']['qiniu']['url'];
            sence_img_process_remote($sence_config_path, $url . '/' . $data['front'], 'front');
            sence_img_process_remote($sence_config_path, $url . '/' . $data['back'], 'back');
            sence_img_process_remote($sence_config_path, $url . '/' . $data['up'], 'up');
            sence_img_process_remote($sence_config_path, $url . '/' . $data['down'], 'down');
            sence_img_process_remote($sence_config_path, $url . '/' . $data['left'], 'left');
            sence_img_process_remote($sence_config_path, $url . '/' . $data['right'], 'right');

            $sence_config_content = file_get_contents($sence_config_path);
            $replaceStrThumb = "%SWFPATH%/images/thumb.jpg";
            $thumbImg = \Qiniu\thumbnail($url . '/' . $data['front'], 1, '188', '188');
            str_replace($sence_config_content, $thumbImg, $tbImg);
            $sence_config_content = str_replace($replaceStrThumb, $thumbImg, $sence_config_content);
            file_put_contents($sence_config_path, $sence_config_content);

            if ($_GPC['treasures']) {
                $sence_config_content = file_get_contents($sence_config_path);
                $sence_config_content = str_replace('%SWFPATH%/spot/1446487094CA8Llf.png', $url . '/' . $_GPC['treasures'], $sence_config_content);
                file_put_contents($sence_config_path, $sence_config_content);
            }

            //版权信息
            $copyRight = Page::getPage(array(7, 8));
            $sence_config_content = file_get_contents($sence_config_path);
            $sence_config_content = str_replace('%SWFPATH%/ui/1446498065z0nkqD.png', $url . '/' . $copyRight[8]['img'], $sence_config_content);
            $sence_config_content = str_replace('13956993061', $copyRight[7]['title'], $sence_config_content);
            file_put_contents($sence_config_path, $sence_config_content);
            //音频设置
            if ($_GPC['audio']) {
                $sence_index_content = file_get_contents($sence_index_path);
                $sence_index_content = str_replace('{$audioPath}', $url . '/' . $_GPC['audio'], $sence_index_content);
                file_put_contents($sence_index_path, $sence_index_content);
            }
        } elseif (!$_W['setting']['remote']['type']) {
            //本地图片处理
            require_once WXZ_PANORAMA . '/source/UtilsImage.class.php';
            sence_img_process($attachdir . $data['front'], $scene_img_path, 'front');
            sence_img_process($attachdir . $data['back'], $scene_img_path, 'back');
            sence_img_process($attachdir . $data['up'], $scene_img_path, 'up');
            sence_img_process($attachdir . $data['down'], $scene_img_path, 'down');
            sence_img_process($attachdir . $data['left'], $scene_img_path, 'left');
            sence_img_process($attachdir . $data['right'], $scene_img_path, 'right');
            UtilsImage::square_crop($attachdir . $data['front'], $scene_img_path . "/thumb.jpg", '188');

            if ($_GPC['treasures']) {
                $sence_config_content = file_get_contents($sence_config_path);
                $sence_config_content = str_replace('%SWFPATH%/spot/1446487094CA8Llf.png', $_W['siteroot'] . $_W['config']['upload']['attachdir'] . '/' . $_GPC['treasures'], $sence_config_content);
                file_put_contents($sence_config_path, $sence_config_content);
            }
            //版权信息
            $copyRight = Page::getPage(array(7, 8));
            $sence_config_content = file_get_contents($sence_config_path);
            $sence_config_content = str_replace('%SWFPATH%/ui/1446498065z0nkqD.png', $_W['siteroot'] . $_W['config']['upload']['attachdir'] . '/' . $copyRight[8]['img'], $sence_config_content);
            $sence_config_content = str_replace('13956993061', $copyRight[7]['title'], $sence_config_content);
            file_put_contents($sence_config_path, $sence_config_content);
            //
            //音频设置
            if ($_GPC['audio']) {
                $sence_index_content = file_get_contents($sence_index_path);
                $sence_index_content = str_replace('{$audioPath}', $_W['siteroot'] . $_W['config']['upload']['attachdir'] . '/' . $_GPC['audio'], $sence_index_content);
                file_put_contents($sence_index_path, $sence_index_content);
            }
        } else {
            //远程附件本地处理
            require_once WXZ_PANORAMA . '/source/UtilsImage.class.php';
            $url = $_W['attachurl_remote'];

            //场景图片处理
            sence_remote_img_process($url . $data['front'], $scene_img_path, 'front');
            sence_remote_img_process($url . $data['back'], $scene_img_path, 'back');
            sence_remote_img_process($url . $data['up'], $scene_img_path, 'up');
            sence_remote_img_process($url . $data['down'], $scene_img_path, 'down');
            sence_remote_img_process($url . $data['left'], $scene_img_path, 'left');
            sence_remote_img_process($url . $data['right'], $scene_img_path, 'right');

            //宝藏图标设置
            if ($_GPC['treasures']) {
                $sence_config_content = file_get_contents($sence_config_path);
                $sence_config_content = str_replace('%SWFPATH%/spot/1446487094CA8Llf.png', $url . $_GPC['treasures'], $sence_config_content);
                file_put_contents($sence_config_path, $sence_config_content);
            }

            //版权信息
            $copyRight = Page::getPage(array(7, 8));
            $sence_config_content = file_get_contents($sence_config_path);
            $sence_config_content = str_replace('%SWFPATH%/ui/1446498065z0nkqD.png', $url . $copyRight[8]['img'], $sence_config_content);
            $sence_config_content = str_replace('13956993061', $copyRight[7]['title'], $sence_config_content);
            file_put_contents($sence_config_path, $sence_config_content);
            //音频设置
            if ($_GPC['audio']) {
                $sence_index_content = file_get_contents($sence_index_path);
                $sence_index_content = str_replace('{$audioPath}', $url . $_GPC['audio'], $sence_index_content);
                file_put_contents($sence_index_path, $sence_index_content);
            }
        }

        message('添加成功', $this->createWebUrl('scene_list'));
    } else {
        message('添加失败', $this->createWebUrl('scene_add'));
    }
}

load()->web('tpl');
include $this->template('web/scene_add');
?>
