<?php

/**
 * 场景相关配置
 */
require_once WXZ_PANORAMA . '/function/global.func.php';
require_once WXZ_PANORAMA . '/source/Page.class.php';

global $_W, $_GPC;

$info = Page::getPage(array(8));

if (checksubmit()) {
    if (!$copyRight) {
        //版权
        $insertData = array(
            'uniacid' => $_W['uniacid'],
            'type' => 8,
            'img' => $_GPC['copyright'],
            'create_at' => time(),
        );
        pdo_insert(Page::$table, $insertData);
    } else {
        pdo_update(Page::$table, array('img' => $_GPC['copyright']), array('id' => $copyRight['id']));
    }

    message('保存成功', $this->createWebUrl('scene_setting'));
}

load()->web('tpl');
include $this->template('web/scene_setting');
?>
