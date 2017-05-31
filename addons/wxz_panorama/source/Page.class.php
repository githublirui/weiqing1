<?php

/**
 * 页面相关
 */
class Page {

    public static $table = 'wxz_panorama_page';

    /**
     * 店铺分类
     * @var type 
     */
    public static $types = array(
        1 => '首页背景图1(640x1154)',
        2 => '首页背景图2(640x1158)',
        3 => '首页背景图3(640x1147)',
        4 => '领奖时间',
        5 => '领奖电话',
        6 => '领奖地址',
        7 => '联系电话',
//        8=> '场景版权'
    );

    /**
     * 获取页面配置类型
     * @return type
     */
    public static function getPageTypes() {
        return self::$types;
    }

    /**
     * 获取页面配置
     * @param type $type 数组或数字
     */
    public static function getPage($type, $field = '*') {
        global $_W;
        $result = array();

        if (!$type) {
            return FALSE;
        }

        $condition = "uniacid={$_W['uniacid']} AND isdel=0";
        if (is_numeric($type)) {
            $condition .= " AND type='{$type}'";
        } else if (is_array($type)) {
            $condition .= " AND type in (" . implode(',', $type) . ")";
        }

        $sql = "SELECT {$field} FROM " . tablename('wxz_panorama_page') . " WHERE {$condition}";

        $list = pdo_fetchall($sql);
        foreach ($list as $row) {
            $result[$row['type']] = $row;
        }
        return $result;
    }

    /**
     * 初始化页面配置
     */
    public static function initPages() {
        global $_W;
        $pageTypes = self::getPageTypes();
        foreach ($pageTypes as $type => $pageType) {
            $page = self::getPage($type, 'id');
            if (!$page) {
                $insertData = array(
                    'uniacid' => $_W['uniacid'],
                    'type' => $type,
                    'create_at' => time(),
                );
                pdo_insert(self::$table, $insertData);
            }
        }
    }

}
