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
    if (!file_exists($dir)) {
        return false;
    }
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

/**
 * 全景七牛远程附件图片处理
 * @param type $sence_config_path
 * @param type $imgUrl
 * @param type $type
 */
function sence_img_process_remote($sence_config_path, $imgUrl, $type) {
    $panoImg = \Qiniu\thumbnail($imgUrl, 1, '1834', '1834');
    $mbImg = \Qiniu\thumbnail($imgUrl, 1, '400', '400');
    $tbImg = \Qiniu\thumbnail($imgUrl, 1, '1500', '1500');

    $sence_config_content = file_get_contents($sence_config_path);
    $replaceStrPano = "%SWFPATH%/images/pano_{$type}.jpg";
    $replaceStrMb = "%SWFPATH%/images/mb_{$type}.jpg";
    $replaceStrTb = "%SWFPATH%/images/tb_{$type}.jpg";

    $sence_config_content = str_replace($replaceStrPano, $panoImg, $sence_config_content);
    $sence_config_content = str_replace($replaceStrMb, $mbImg, $sence_config_content);
    $sence_config_content = str_replace($replaceStrTb, $tbImg, $sence_config_content);
    file_put_contents($sence_config_path, $sence_config_content);
}

?>
