<?php

global $_W, $_GPC;

$id = $_GPC['id'];
$activity_info_sql = "SELECT * FROM " . tablename('wxz_panorama_activity') . " WHERE id={$id}";
$activity_info = pdo_fetch($activity_info_sql);
if (!$activity_info) {
    message('活动不存在', $this->createWebUrl('activity_list'));
}

load()->web('tpl');
if (checksubmit()) {
    $data = array(
        'name' => (string) $_GPC['name'],
        'date_start' => (string) $_GPC['date_start'],
        'date_end' => (string) $_GPC['date_end'],
        'force_follow' => (int) $_GPC['force_follow'],
        'force_follow_url' => (string) $_GPC['force_follow_url'],
        'max_award_num' => (int) $_GPC['max_award_num'],
        'verification_code' => (string) $_GPC['verification_code'],
        'update_time' => time(),
    );

    if (pdo_update('wxz_panorama_activity', $data, array('id' => $id))) {
        message('更新成功', $this->createWebUrl('activity_list'));
    } else {
        message('更新失败', $this->createWebUrl('activity_edit', array('id' => $id)));
    }
}
include $this->template('web/activity_edit');
?>
