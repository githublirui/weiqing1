<?php

global $_W, $_GPC;

$id = $_GPC['id'];
$shop_info_sql = "SELECT * FROM " . tablename('wxz_openeye_shop') . " WHERE id={$id}";
$shop_info = pdo_fetch($shop_info_sql);
if (!$shop_info) {
    message('奖品不存在', $this->createWebUrl('award_manage'));
}

load()->web('tpl');
if (checksubmit()) {
    //字段验证, 并获得正确的数据$dat
    $data = array(
        'name' => $_GPC['name'],
        'charges_percent' => $_GPC['charges_percent'],
    );

    if (pdo_update('wxz_openeye_shop', $data, array('id' => $id))) {
        message('更新成功', $this->createWebUrl('shop_list'));
    } else {
        message('更新失败', $this->createWebUrl('shop_edit', array('id' => $id)));
    }
}

include $this->template('web/shop_edit');
?>
