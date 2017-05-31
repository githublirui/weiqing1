<?php

global $_W, $_GPC;

$id = $_GPC['id'];
$award_info_sql = "SELECT * FROM " . tablename('wxz_panorama_award') . " WHERE id={$id}";
$award_info = pdo_fetch($award_info_sql);
if (!$award_info) {
    message('奖品不存在', $this->createWebUrl('award_list'));
}
load()->web('tpl');
if (checksubmit()) {
    $_GPC['name'] = $_GPC['type'] == 1 ? '现金' : $_GPC['name'];
    //字段验证, 并获得正确的数据$dat
    $data = array(
        'name' => $_GPC['name'],
        'num' => (int) $_GPC['num'],
        'probability' => $_GPC['probability'],
    );

    if ($_GPC['type'] == 1) {
        $data['min_money'] = (int) $_GPC['min_money'];
        $data['max_money'] = (int) $_GPC['max_money'];
    }

    if ($_GPC['num'] < $coupon_info['cashed']) {
        message('总数量不能小于已兑换数量', $this->createWebUrl('award_edit', array('id' => $id)));
    }

    //加库存
    if ($_GPC['num'] > $award_info['num']) {
        $data['left_num'] = $award_info['left_num'] + ($_GPC['num'] - $award_info['num']);
    } else {
        //减库存
        $data['left_num'] = $award_info['left_num'] - ($award_info['num'] - $_GPC['num']);
    }

    if (pdo_update('wxz_panorama_award', $data, array('id' => $id))) {
        message('更新成功', $this->createWebUrl('award_list'));
    } else {
        message('更新失败', $this->createWebUrl('award_add'));
    }
}
include $this->template('web/award_edit');
?>
