<?php

/**
 * 页面相关
 */
class Page {

    public static $table = 'wxz_easy_pay_page';

    /**
     * 页面模版元素
     * @var type 
     */
    public static $types = array(
        1 => array(
            'name' => '同意协议',
            'placeholder' => '同意「简单支付平台使用协议」',
        ),
        2 => array(
            'name' => '支付用户使用协议内容',
            'type' => 'textarea',
        ),
        3 => array(
            'name' => '底部左侧关于文案',
            'placeholder' => '关于简单支付',
        ),
        4 => array(
            'name' => '底部关于右侧文案',
            'placeholder' => '商务合作，意见反馈：138xxxxxxxx',
        ),
        5 => array(
            'name' => '底部客服头像',
            'type' => 'img',
        ),
        6 => array(
            'name' => '底部客服名称',
            'placeholder' => '简单支付客服-小m',
        ),
        7 => array(
            'name' => '底部客服头像右侧说明',
            'placeholder' => '微商利器 自动收款统计订单',
        ),
        8 => array(
            'name' => '底部二维码',
            'type' => 'img',
        ),
        9 => array(
            'name' => '底部二维码说明',
            'placeholder' => '扫描上面的二维码，加简单支付客服微信',
        ),
        10 => array(
            'name' => '使用帮助地址',
            'type' => 'link',
            'placeholder' => '请输入帮助页面跳转链接',
        ),
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

        $condition = "uniacid={$_W['uniacid']}";
        if (is_numeric($type)) {
            $condition .= " AND type='{$type}'";
        } else if (is_array($type)) {
            $condition .= " AND type in (" . implode(',', $type) . ")";
        }

        $sql = "SELECT {$field} FROM " . tablename(self::$table) . " WHERE {$condition}";

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
