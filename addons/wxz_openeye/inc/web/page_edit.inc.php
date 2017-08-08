<?php

global $_W, $_GPC;

require_once WXZ_OPENEYE . '/source/Page.class.php';

$pages = Page::$pages;
$pageTypes = Page::$pageTypes;


$id = $_GPC['id'];
$page_info_sql = "SELECT * FROM " . tablename('wxz_openeye_page') . " WHERE id={$id}";
$page_info = pdo_fetch($page_info_sql);
if (!$page_info) {
    message('视频不存在', $this->createWebUrl('page_list'));
}

load()->web('tpl');
if (checksubmit()) {
    //字段验证, 并获得正确的数据$dat
    $data = array(
        'page' => $_GPC['page'],
        'page_type' => $_GPC['page_type'],
        'group' => $_GPC['group'],
        'name' => $_GPC['name'],
        'pic' => $_GPC['pic'],
        'time' => $_GPC['time'],
        'author' => $_GPC['author'],
        'category' => $_GPC['category'],
        'avatar' => $_GPC['avatar'],
        'upload_date' => $_GPC['upload_date'],
        'address' => $_GPC['address'],
    );

    if (pdo_update('wxz_openeye_page', $data, array('id' => $id))) {
        message('更新成功', $this->createWebUrl('page_list'));
    } else {
        message('更新失败', $this->createWebUrl('page_edit', array('id' => $id)));
    }
}

include $this->template('web/page_edit');
?>
