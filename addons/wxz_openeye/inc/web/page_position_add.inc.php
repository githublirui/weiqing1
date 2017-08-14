<?php

global $_W, $_GPC;
require_once WXZ_OPENEYE . '/source/Page.class.php';

$pages = Page::$pages;
$pageTypes = Page::$pageTypes;
$sql = "SELECT * FROM " . tablename('wxz_openeye_page') . " ORDER BY `id` DESC";
$videos = pdo_fetchall($sql);

if (checksubmit()) {
    //字段验证, 并获得正确的数据$dat
    $data = array(
        'uniacid' => $_W['uniacid'],
        'page' => $_GPC['page'],
        'page_type' => $_GPC['page_type'],
        'page_id' => $_GPC['page_id'],
        'create_time' => time(),
    );
    if (pdo_insert('wxz_openeye_page_position', $data)) {
        message('添加成功', $this->createWebUrl('page_position_list'));
    } else {
        message('添加失败', $this->createWebUrl('page_position_add'));
    }
}
load()->web('tpl');
include $this->template('web/page_position_add');
?>
