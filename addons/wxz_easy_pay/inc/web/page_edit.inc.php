<?php

global $_W, $_GPC;

$id = $_GPC['id'];
$info_sql = "SELECT * FROM " . tablename('wxz_easy_pay_page') . " WHERE id={$id}";
$info = pdo_fetch($info_sql);

if (!$info) {
    message('页面配置不存在', $this->createWebUrl('page_list'));
}

load()->web('tpl');
require_once WXZ_EASY_PAY . '/source/Page.class.php';
$pageTypes = Page::getPageTypes();

$pageTypeInfo = $pageTypes[$info['type']];
$pageTypeInfo['type'] = $pageTypeInfo['type'] ? $pageTypeInfo['type'] : 'text';

if (checksubmit()) {
    //字段验证, 并获得正确的数据$dat
    $data = array(
        'title' => $_GPC['title'],
        'img' => $_GPC['img'],
        'link' => $_GPC['link'],
        'desc' => $_GPC['desc'],
        'update_at' => time(),
    );

    if (pdo_update('wxz_easy_pay_page', $data, array('id' => $id))) {
        message('更新成功', $this->createWebUrl('page_list'));
    } else {
        message('更新失败', $this->createWebUrl('page_edit', array('id' => $id)));
    }
}
include $this->template("web/page_edit_{$pageTypeInfo['type']}");
?>
