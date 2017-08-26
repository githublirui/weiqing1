<?php

global $_W, $_GPC;

if (checksubmit()) {
    //字段验证, 并获得正确的数据$dat
    $data = array(
        'uniacid' => $_W['uniacid'],
        'name' => $_GPC['name'],
        'pic' => $_GPC['pic'],
        'desc' => $_GPC['desc'],
        'create_time' => time(),
    );
    if (pdo_insert('wxz_openeye_special', $data)) {
        message('添加成功', $this->createWebUrl('special_list'));
    } else {
        message('添加失败', $this->createWebUrl('special_add'));
    }
}
load()->web('tpl');
include $this->template('web/special_add');
?>
