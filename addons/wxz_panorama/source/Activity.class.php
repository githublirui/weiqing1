<?php

/**
 * 活动
 */
class Activity {

    public static $table = 'wxz_panorama_activity';

    /**
     * 获取所有项目
     */
    public static function getAll($field = '*') {
        global $_W;
        $condition = "uniacid={$_W['uniacid']}";
        $sql = "SELECT {$field} FROM " . tablename(self::$table) . " WHERE {$condition} order by `id` desc";
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
            //删除项目
            require_once WXZ_PANORAMA . '/source/Project.class.php';
            Project::delByAid($id);
        }
        return true;
    }

}
