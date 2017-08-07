<?php

global $_W, $_GPC;
require_once WXZ_OPENEYE . '/source/Page.class.php';

$pages = Page::$pages;
$pageTypes = Page::$pageTypes;


if (checksubmit()) {
    //字段验证, 并获得正确的数据$dat
    $data = array(
        'uniacid' => $_W['uniacid'],
        'page' => $_GPC['page'],
        'page_type' => $_GPC['page_type'],
        'group' => $_GPC['group'],
        'name' => $_GPC['name'],
        'time' => $_GPC['time'],
        'author' => $_GPC['author'],
        'category' => $_GPC['category'],
        'avatar' => $_GPC['avatar'],
        'upload_date' => $_GPC['upload_date'],
        'address' => $_GPC['address'],
        'create_time' => time(),
    );
    if (pdo_insert('wxz_openeye_page', $data)) {
        message('添加成功', $this->createWebUrl('page_list'));
    } else {
        message('添加失败', $this->createWebUrl('page_add'));
    }
}
load()->web('tpl');
include $this->template('web/page_add');
?>
