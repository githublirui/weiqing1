<?php

/**
 * 全景场景类
 */
class Scene {

    public static $table = 'wxz_panorama_scene';

    /**
     * 获取所有场景
     */
    public static function getAllScene($field = '*') {
        global $_W;
        $condition = "uniacid={$_W['uniacid']}";
        $sql = "SELECT {$field} FROM " . tablename(self::$table) . " WHERE {$condition} order by id ASC";
        return pdo_fetchall($sql);
    }

    /**
     * 通过id获取场景信息
     * @global type $_W
     * @param type $id
     * @param type $field
     * @return type
     */
    public static function getById($id, $field = '*') {
        global $_W;
        $condition = "uniacid={$_W['uniacid']} AND id={$id}";
        $sql = "SELECT {$field} FROM " . tablename(self::$table) . " WHERE {$condition}";
        return pdo_fetch($sql);
    }

    /**
     * 通过项目删除场景
     * @param type $proId
     */
    public static function delSceneByProId($proId) {
        global $_W;
        return pdo_delete(self::$table, array('uniacid' => $_W['uniacid'], 'project_id' => $proId));
    }

    /**
     * 通过id删除项目
     * @param type $id
     */
    public static function delById($id) {
        global $_W;
        return pdo_delete(self::$table, array('id' => $id, 'uniacid' => $_W['uniacid']));
    }

    /**
     * 获取保存全景图片的地址
     * @param $src 图片地址
     */
    public static function getSceneImageSrc($src) {
        global $_W;
        if (!$src) {
            return;
        }

        require_once WXZ_PANORAMA . '/source/UtilsImage.class.php';

        $result['pano'] = $src;
        $thumbZoon = 2; //手机图片缩小倍数
        $fileUrl = tomedia($src);
        setting_load('remote');
        $imageInfo = getimagesize($fileUrl);
        $thumbWidth = ceil($imageInfo[0] / $thumbZoon); //缩小一倍
        $thumbHeight = ceil($imageInfo[1] / $thumbZoon);

        $attachdir = IA_ROOT . '/' . $_W['config']['upload']['attachdir'] . '/';
        $type = $_W['setting']['remote']['type'];
        if (!$type) {
            //本地处理,本地生成缩略图
            $filePath = $attachdir . $src;
            $fileInfo = pathinfo($filePath);
            $mobielFilename = 'mobile_' . $fileInfo['filename'] . '.' . $fileInfo['extension'];
            $mobilePath = $fileInfo['dirname'] . '/' . $mobielFilename;
            UtilsImage::square_crop($filePath, $mobilePath, $thumbWidth);
            $result['mobile'] = str_replace($attachdir, '', $mobilePath);
            return $result;
        }

        //远程图片处理
        switch ($type) {
            case 2:
                //阿里云设置
                $result['mobile'] = $src . "?x-oss-process=image/resize,m_mfit,h_{$thumbWidth},w_{$thumbHeight}";
                break;
            case 3:
                //七牛设置
                $result['mobile'] = $src . "?imageView2/2/w/{$thumbWidth}/h/{$thumbHeight}";
                break;
            case 4:
                //腾讯云
                $result['mobile'] = $src;
        }

        return $result;
    }

}
