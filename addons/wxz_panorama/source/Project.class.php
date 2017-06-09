<?php

/**
 * 全景项目类
 */
class Project {

    public static $table = 'wxz_panorama_project';

    /**
     * 获取所有项目
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
     * 通过id删除项目
     * @param type $id
     */
    public static function delById($id) {
        global $_W;
        $ret = pdo_delete(self::$table, array('id' => $id, 'uniacid' => $_W['uniacid']));
        if ($ret) {
            //删除场景
            require_once WXZ_PANORAMA . '/source/Scene.class.php';
            Scene::delSceneByProId($id);
        }
        return true;
    }

}
