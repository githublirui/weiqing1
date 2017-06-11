<?php

/**
 * 全景场景类
 */
class Scene {

    public static $table = 'wxz_panorama_scene';

    /**
     * 获取项目下所有场景
     */
    public static function getScenesByProId($proId, $field = '*') {
        global $_W;
        if (!$proId) {
            return false;
        }
        $condition = "uniacid={$_W['uniacid']} AND project_id={$proId}";
        $sql = "SELECT {$field} FROM " . tablename(self::$table) . " WHERE {$condition} order by id ASC";
        $scenes = pdo_fetchall($sql);
        foreach ($scenes as $k => $scene) {
            $scenes[$k] = self::formatRow($scene);
        }
        return $scenes;
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
        if (!$id) {
            return false;
        }
        $condition = "uniacid={$_W['uniacid']} AND id={$id}";
        $sql = "SELECT {$field} FROM " . tablename(self::$table) . " WHERE {$condition}";
        $scene_info = pdo_fetch($sql);
        //格式化存储字段
        foreach ($scene_info as $column => $value) {
            $scene_info[$column] = self::formatColumn($column, $value);
        }
        return $scene_info;
    }

    /**
     * 格式化一条字段
     * @param type $scene
     */
    public static function formatRow($scene_info) {
        foreach ($scene_info as $column => $value) {
            $scene_info[$column] = self::formatColumn($column, $value);
        }
        return $scene_info;
    }

    /*
     * 格式化数据库字段
     * @param type $column
     * @param type $value
     */

    public static function formatColumn($column, $value) {
        $img_columns = array('front', 'back', 'up', 'down', 'left', 'right'); //六张图片
        if (in_array($column, $img_columns) && $value) {
            $value = unserialize($value);
        }
        return $value;
    }

    /**
     * 通过项目删除场景
     * @param type $proId
     */
    public static function delSceneByProId($proId) {
        global $_W;
        if (!$proId) {
            return false;
        }
        return pdo_delete(self::$table, array('uniacid' => $_W['uniacid'], 'project_id' => $proId));
    }

    /**
     * 通过id删除项目
     * @param type $id
     */
    public static function delById($id) {
        global $_W;
        if (!$id) {
            return false;
        }
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

    /**
     * 创建xml标签
     * @param type $name
     * @param type $attributes
     * @param type $element
     * @param type $close false单标签 true闭合标签
     */
    public static function createTag($name, $attributes = '', $element = '', $close = false) {
        $result = "<$name";
        if (is_array($attributes)) {
            foreach ($attributes as $k => $attribute) {
                $result .= ' ' . $k . '="' . $attribute . '" ';
            }
        } else {
            $result .= " $attributes";
        }

        if ($close) {
            $result .= " >\r\n";
        } else {
            $result .= " />\r\n";
        }
        //添加标签内容
        if ($element) {
            $result .= "    " . $element;
        }
        if ($close) {
            $result .= "\r\n" . self::creaeCloseTag($name);
        }
        return $result;
    }

    /**
     * 创建关闭标签
     * @param type $name
     */
    public static function creaeCloseTag($name) {
        return "</$name>\r\n";
    }

    /**
     * 根据场景信息创建场景图片标签
     * @param type $scene
     */
    public static function createSceneImage($scene) {
        $img_columns = array('front', 'back', 'up', 'down', 'left', 'right'); //六张图片
        $imgElement = '';
        foreach ($img_columns as $img_column) {
            if ($scene[$img_column]) {
                $attributes = array(
                    'url' => tomedia($scene[$img_column]['pano']),
                    'rotate' => '0',
                    'flip' => '0',
                );
                $imgElement .= self::createTag($img_column, $attributes);
                //手机端
                $attributes = array(
                    'url' => tomedia($scene[$img_column]['pano']),
                    'rotate' => '0',
                    'flip' => '0',
                );
                $mobileImgElement .= self::createTag($img_column, $attributes);
                $imgElement .= self::createTag('mobile', array(), $mobileImgElement, true);
            }
        }

        //图片属性
        $attributes = array(
        );
        return self::createTag('image', $attributes, $imgElement, true);
    }

    /**
     * 分配宝藏逻辑
     * @param type $sceneNum 场景数
     * @param type $treasureNum 宝藏数量
     */
    public static function distribTreasure($sceneNum, $treasureNum) {
        $result = [];
        $disNum = ceil($treasureNum / $sceneNum); //可以分配的数量
        $disNum = $disNum == 0 ? 1 : $disNum; //可以分配的数量
        //场景数量大于宝藏数量，平均分配一个
        while (true) {
            if ($treasureNum <= 0) {
                break;
            }
            for ($i = 0; $i < $sceneNum; $i++) {
                if ($treasureNum <= 0) {
                    break;
                }
                if ($result[$i] < $disNum) {
                    $rand = rand(0, $sceneNum);
                    if ($rand == 0) {
                        $result[$i] ++;
                        $treasureNum--;
                    }
                }
            }
        }
        return $result;
    }

}
