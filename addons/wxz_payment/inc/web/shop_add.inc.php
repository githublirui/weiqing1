<?php

global $_W, $_GPC;

if (checksubmit()) {
    //字段验证, 并获得正确的数据$dat
    $data = array(
        'uniacid' => $_W['uniacid'],
        'name' => $_GPC['name'],
        'charges_percent' => $_GPC['charges_percent'],
        'created' => time(),
    );
    if (pdo_insert('wxz_payment_shop', $data)) {
        message('添加成功', $this->createWebUrl('shop_list'));
    } else {
        message('添加失败', $this->createWebUrl('shop_add'));
    }
}
load()->web('tpl');
include $this->template('web/shop_add');
?>
