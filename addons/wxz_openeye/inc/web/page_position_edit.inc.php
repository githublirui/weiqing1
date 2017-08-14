<?php

global $_W, $_GPC;

require_once WXZ_OPENEYE . '/source/Page.class.php';

$pages = Page::$pages;
$pageTypes = Page::$pageTypes;
$sql = "SELECT * FROM " . tablename('wxz_openeye_page') . " ORDER BY `id` DESC";
$videos = pdo_fetchall($sql);

$id = $_GPC['id'];
$page_position_info_sql = "SELECT * FROM " . tablename('wxz_openeye_page_position') . " WHERE id={$id}";
$page_position_info = pdo_fetch($page_position_info_sql);
if (!$page_position_info) {
    message('视频位置不存在', $this->createWebUrl('page_position_list'));
}

load()->web('tpl');
if (checksubmit()) {
    //字段验证, 并获得正确的数据$dat
    $data = array(
        'page' => $_GPC['page'],
        'page_type' => $_GPC['page_type'],
        'page_id' => $_GPC['page_id'],
    );

    if (pdo_update('wxz_openeye_page_position', $data, array('id' => $id))) {
        message('更新成功', $this->createWebUrl('page_position_list'));
    } else {
        message('更新失败', $this->createWebUrl('page_position_edit', array('id' => $id)));
    }
}

include $this->template('web/page_position_edit');
?>
