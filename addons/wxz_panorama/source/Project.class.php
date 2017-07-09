<?php

/**
 * 全景项目类
 */
class Project {

    public static $table = 'wxz_panorama_project';
    public static $config_table = 'wxz_panorama_project_config';

    /**
     * 获取所有项目
     */
    public static function getAll($field = '*') {
        global $_W;
        $condition = "uniacid={$_W['uniacid']}";
        $sql = "SELECT {$field} FROM " . tablename(self::$table) . " WHERE {$condition} order by `sort_order` desc";
        return pdo_fetchall($sql);
    }

    /**
     * 获取第一个项目
     */
    public static function getFirstPro() {
        global $_W;
        $condition = "uniacid={$_W['uniacid']}";
        $sql = "SELECT * FROM " . tablename(self::$table) . " WHERE {$condition} order by `sort_order` desc limit 1";
        return pdo_fetch($sql);
    }

    /**
     * 获取下一个项目id
     * @param type $pid
     */
    public static function getNextProject($pid) {
        global $_W;
        $proInfo = self::getById($pid, 'sort_order');
        if (!$proInfo) {
            return self::getFirstPro();
        }

        $condition = "uniacid={$_W['uniacid']} AND sort_order<={$proInfo['sort_order']} AND id !={$pid}";
        $sql = "SELECT * FROM " . tablename(self::$table) . " WHERE {$condition} order by `sort_order` desc,id desc";
        $nextInfo = pdo_fetch($sql);
        if (!$nextInfo) {
            return self::getFirstPro(); //没有下一个场景，返回第一个场景
        }
        return $nextInfo;
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
     * 通过id获取场景配置信息
     * @global type $_W
     * @param type $pid
     * @param type $field
     * @return type
     */
    public static function getConfigById($pid, $field = '*') {
        global $_W;
        if (!$pid) {
            return false;
        }
        $condition = "uniacid={$_W['uniacid']} AND project_id={$pid}";
        $sql = "SELECT {$field} FROM " . tablename(self::$config_table) . " WHERE {$condition}";
        $info = pdo_fetch($sql);
        if ($info['config']) {
            $info['config'] = unserialize($info['config']);
        }
        return $info;
    }

    /**
     * 更新配置信息
     * @param type $pid
     * @param type $data
     */
    public static function updateConfig($pid, $data) {
        global $_W;

        $data['update_at'] = time();

        $condition = "uniacid={$_W['uniacid']} AND project_id={$pid}";
        $sql = "SELECT id FROM " . tablename(self::$config_table) . " WHERE {$condition}";
        $config = pdo_fetch($sql);
        if (!$config) {
            $data['uniacid'] = $_W['uniacid'];
            $data['project_id'] = $pid;
            pdo_insert(self::$config_table, $data);
        } else {
            pdo_update(self::$config_table, $data);
        }
        return true;
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
            require_once WXZ_PANORAMA . '/source/Hotspot.class.php';
            Scene::delSceneByProId($id);
            Hotspot::delByProId($id);
        }
        return true;
    }

}
