<?php

/**
 * 全景热点
 */
class Hotspot {

    public static $table = 'wxz_panorama_scene_hotspot';

    /**
     * 支持热点所有的操作
     * @var type 
     */
    public static $actions = array(
        1 => '场景跳转',
        2 => '弹出图片',
        3 => '跳转链接',
    );

    /**
     * 支持热点所有的类型
     * @var type 
     */
    public static $types = array(
        1 => '简易热点',
//        2 => '透明热点',
//        3 => '自由图片',
//        4 => '视频热区',
    );

    /**
     * 获取场景所有热点
     */
    public static function getAll($sid, $field = '*') {
        global $_W;
        if (!$sid) {
            return false;
        }
        $condition = "uniacid={$_W['uniacid']} AND scene_id={$sid}";
        $sql = "SELECT {$field} FROM " . tablename(self::$table) . " WHERE {$condition}";
        return pdo_fetchall($sql);
    }

    /**
     * 通过id更新
     * @param type $id
     * @param type $data
     */
    public static function updateById($id, $data) {
        return pdo_update(self::$table, $data, array('id' => $id));
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
        $info = pdo_fetch($sql);
        if ($info['config']) {
            $info['config'] = unserialize($info['config']);
        }
        return $info;
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
     * @desc
     * @param
     * @return
     */
    public static function delBySceneId($id) {
        global $_W;
        $condition = "uniacid={$_W['uniacid']} AND scene_id={$id}";
        $sql = "DELETE FROM " . tablename(self::$table) . " WHERE {$condition}";
        return pdo_query($sql);
    }

    /**
     * @desc
     * @param
     * @return
     */
    public static function delByProId($id) {
        global $_W;
        $condition = "uniacid={$_W['uniacid']} AND project_id={$id}";
        $sql = "DELETE FROM " . tablename(self::$table) . " WHERE {$condition}";
        return pdo_query($sql);
    }

}
