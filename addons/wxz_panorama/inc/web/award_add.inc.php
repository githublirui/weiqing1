<?php

global $_W, $_GPC;

$aid = intval($_GPC['aid']);
require_once WXZ_PANORAMA . '/source/Activity.class.php';
$activityInfo = Activity::getById($aid, 'id,name');

if (checksubmit()) {
    $_GPC['name'] = $_GPC['type'] == 1 ? '现金' : $_GPC['name'];
    //字段验证, 并获得正确的数据$dat
    $data = array(
        'uniacid' => $_W['uniacid'],
        'aid' => $aid,
        'type' => (int) $_GPC['type'],
        'min_money' => (int) $_GPC['min_money'],
        'max_money' => (int) $_GPC['max_money'],
        'name' => (string) $_GPC['name'],
        'num' => (int) $_GPC['num'],
        'left_num' => (int) $_GPC['num'],
        'probability' => (int) $_GPC['probability'],
        'create_time' => time(),
    );
    if (pdo_insert('wxz_panorama_award', $data)) {
        message('添加成功', $this->createWebUrl('award_list', array('aid' => $aid)));
    } else {
        message('添加失败', $this->createWebUrl('award_add', array('aid' => $aid)));
    }
}

load()->web('tpl');
include $this->template('web/award_add');
?>
