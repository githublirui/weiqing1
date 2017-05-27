<?php

/*
 * 获取指定模版
 */

function get_real_tpl($tpl_name) {
    global $_GPC;
    return $tpl_name;
}

function getfilecounts($ff) {
    $dir = './' . $ff;
    $handle = opendir($dir);
    $sences = array();
    while (false !== $file = (readdir($handle))) {
        if ($file !== '.' && $file != '..' && !is_file($file)) {
            $sences[] = $file;
        }
    }
    closedir($handle);
    return $sences;
}

function copy_dir($src, $dst) {
    $dir = opendir($src);
    @mkdir($dst);
    while (false !== ( $file = readdir($dir))) {
        if (( $file != '.' ) && ( $file != '..' )) {
            if (is_dir($src . '/' . $file)) {
                copy_dir($src . '/' . $file, $dst . '/' . $file);
                continue;
            } else {
                copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}

/**
 * 全景场景图片处理
 * @param type $source
 * @param type $type
 */
function sence_img_process($source, $destination, $type) {
    require_once WXZ_PANORAMA . '/source/UtilsImage.class.php';
    //mb 400 x 400,pano 1834,tb 1500 x 1500
    UtilsImage::square_crop($source, $destination . "/pano_{$type}.jpg", '1834');
    UtilsImage::square_crop($source, $destination . "/mb_{$type}.jpg", '400');
    UtilsImage::square_crop($source, $destination . "/tb_{$type}.jpg", '1500');
}

?>
