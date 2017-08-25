<?php

global $_W, $_GPC;

if (checksubmit()) {
    $data = array(
        'uniacid' => $_W['uniacid'],
        'name' => (string) $_GPC['name'],
        'date_start' => (string) $_GPC['date_start'],
        'date_end' => (string) $_GPC['date_end'],
        'force_follow' => (int) $_GPC['force_follow'],
        'force_follow_url' => (string) $_GPC['force_follow_url'],
        'max_award_num' => (int) $_GPC['max_award_num'],
        'verification_code' => (string) $_GPC['verification_code'],
        'create_time' => time(),
    );
    if (pdo_insert('wxz_panorama_activity', $data)) {
        message('添加成功', $this->createWebUrl('activity_list'));
    } else {
        message('添加失败', $this->createWebUrl('activity_add'));
    }
}

load()->web('tpl');
include $this->template('web/activity_add');
?>
