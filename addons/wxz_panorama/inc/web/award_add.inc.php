<?php

global $_W, $_GPC;

if (checksubmit()) {
    //字段验证, 并获得正确的数据$dat
    $data = array(
        'uniacid' => $_W['uniacid'],
        'name' => $_GPC['name'],
        'num' => (int) $_GPC['num'],
        'left_num' => (int) $_GPC['num'],
        'probability' => (int) $_GPC['probability'],
        'create_time' => time(),
    );
    if (pdo_insert('wxz_panorama_award', $data)) {
        message('添加成功', $this->createWebUrl('award_list'));
    } else {
        message('添加失败', $this->createWebUrl('award_add'));
    }
}

load()->web('tpl');
include $this->template('web/award_add');
?>
