<?php

global $_W, $_GPC;

$id = $_GPC['id'];
$info_sql = "SELECT * FROM " . tablename('wxz_panorama_page') . " WHERE id={$id} AND isdel=0";
$info = pdo_fetch($info_sql);
if (!$info) {
    message('页面配置不存在', $this->createWebUrl('page_list'));
}

load()->web('tpl');
require_once WXZ_PANORAMA . '/source/Page.class.php';

if (checksubmit()) {
    switch ($info['type']) {
        case 3:
            break;
        case 9:
            break;
    }

    //字段验证, 并获得正确的数据$dat
    $data = array(
        'title' => $_GPC['title'],
        'img' => $_GPC['img'],
        'link' => $_GPC['link'],
        'desc' => $_GPC['desc'],
        'update_at' => time(),
    );

    if (pdo_update('wxz_panorama_page', $data, array('id' => $id))) {
        message('更新成功', $this->createWebUrl('page_list'));
    } else {
        message('更新失败', $this->createWebUrl('page_edit', array('id' => $id)));
    }
}

switch ($info['type']) {
    case 1:case 2:case 3:
        include $this->template('web/page_edit_img');
        break;
    default:
        include $this->template('web/page_edit_title');
        break;
}
?>
