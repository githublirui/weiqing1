<?php

/**
 * 页面相关
 */
class Page {

    public static $table = 'wxz_shoppingmall_page';

    /**
     * 店铺分类
     * @var type 
     */
    public static $types = array(
        1 => '商场地址',
        2 => '咨询电话',
        3 => '商场首页底部导航',
        4 => '营业时间',
        5 => '会员卡图片(440x270)',
        6 => '会员卡图片(未注册440x270)',
        7 => '注册页背景图(1080x830)',
        8 => '完善资料页背景图(1080x830)',
        9 => '商户首页导航',
//        10=> '场景版权'
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

        $sql = "SELECT {$field} FROM " . tablename('wxz_shoppingmall_page') . " WHERE {$condition}";

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
