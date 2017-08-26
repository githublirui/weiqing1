<?php

global $_W, $_GPC;

$id = $_GPC['id'];
$special_info_sql = "SELECT * FROM " . tablename('wxz_openeye_special') . " WHERE id={$id}";
$special_info = pdo_fetch($special_info_sql);
if (!$special_info) {
    message('专题不存在', $this->createWebUrl('special_list'));
}

load()->web('tpl');
if (checksubmit()) {
    //字段验证, 并获得正确的数据$dat
    $data = array(
        'name' => $_GPC['name'],
        'desc' => $_GPC['desc'],
        'pic' => $_GPC['pic'],
    );

    if (pdo_update('wxz_openeye_special', $data, array('id' => $id))) {
        message('更新成功', $this->createWebUrl('special_list'));
    } else {
        message('更新失败', $this->createWebUrl('special_edit', array('id' => $id)));
    }
}

include $this->template('web/special_edit');
?>
