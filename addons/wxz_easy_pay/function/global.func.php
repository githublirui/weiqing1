<?php

function imageCropper($source_path, $target_width, $target_height) {
    $source_info = getimagesize($source_path);
    $source_width = $source_info[0];
    $source_height = $source_info[1];
    $source_mime = $source_info['mime'];
    $source_ratio = $source_height / $source_width;
    $target_ratio = $target_height / $target_width;
    if ($source_ratio > $target_ratio) {
        // image-to-height
        $cropped_width = $source_width;
        $cropped_height = $source_width * $target_ratio;
        $source_x = 0;
        $source_y = ($source_height - $cropped_height) / 2;
    } elseif ($source_ratio < $target_ratio) {
        //image-to-widht
        $cropped_width = $source_height / $target_ratio;
        $cropped_height = $source_height;
        $source_x = ($source_width - $cropped_width) / 2;
        $source_y = 0;
    } else {
        //image-size-ok
        $cropped_width = $source_width;
        $cropped_height = $source_height;
        $source_x = 0;
        $source_y = 0;
    }
    switch ($source_mime) {
        case 'image/gif':
            $source_image = imagecreatefromgif($source_path);
            break;
        case 'image/jpeg':
            $source_image = imagecreatefromjpeg($source_path);
            break;
        case 'image/png':
            $source_image = imagecreatefrompng($source_path);
            break;
        default:
            return;
            break;
    }
    $target_image = imagecreatetruecolor($target_width, $target_height);
    $cropped_image = imagecreatetruecolor($cropped_width, $cropped_height);
    // copy
    imagecopy($cropped_image, $source_image, 0, 0, $source_x, $source_y, $cropped_width, $cropped_height);
    // zoom
    imagecopyresampled($target_image, $cropped_image, 0, 0, 0, 0, $target_width, $target_height, $cropped_width, $cropped_height);
    return $target_image;

    header('Content-Type: image/jpeg');
    imagejpeg($target_image, null, 100);
    imagedestroy($source_image);
    imagedestroy($target_image);
    imagedestroy($cropped_image);
}

/**
 * 上传图片
 */
function uploadFile() {
    global $_GPC, $_W;

    $uploadDir = IA_ROOT . '/' . $_W['config']['upload']['attachdir'] . '/wxz_easy_pay/' . date('Ymd') . '/';
    $uploadUrl = $_W['siteroot'] . $_W['config']['upload']['attachdir'] . '/wxz_easy_pay/' . date('Ymd') . '/';
    //上传商品图片	image/jpeg							
    $valid_formats = array("image/gif", "image/jpeg", "image/png", "image/bmp", "image/pjpeg", "application/octet-stream");

    load()->func('file');

    if (!is_file($uploadDir)) {
        mkdirs($uploadDir);
    }
    $name = $_FILES['file']['name'][0];
    $size = $_FILES['file']['size'][0];
    $type = $_FILES['file']['type'][0];
    if ($_FILES['file']['error'][0] == 1) {
        message('上传图片太大，请修改服务器配置');
    } else if ($_FILES['file']['error'][0] != 0) {
        message('上传出错');
    }

    if (strlen($name)) {
        if (in_array($type, $valid_formats)) {
            // if(1){  
            if ($size < (1024 * 1024 * 8)) {

                $actual_image_name = time() . rand(1, 11) . ".jpg";
                $tmp = $_FILES['file']['tmp_name'][0];
                load()->func('file');

                if (move_uploaded_file($tmp, $uploadDir . $actual_image_name)) {
                    $p_img_url = $uploadUrl . $actual_image_name;
                    //
                } else {
                    message('上传失败');
                }
            } else {
                message('上传图片最大 2 MB');
            }
        } else {
            message('无效的图片格式');
        }
    } else {
        message('请选择图片');
    }
    return $p_img_url;
}

?>
