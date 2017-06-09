<?php

/**
 * 添加项目
 */
global $_W, $_GPC;

if (checksubmit()) {
    //字段验证, 并获得正确的数据$dat
    $data = array(
        'uniacid' => $_W['uniacid'],
        'name' => (string) $_GPC['name'],
        'create_at' => time(),
    );
    if (pdo_insert('wxz_panorama_project', $data)) {
        message('添加成功', $this->createWebUrl('project_list'));
    } else {
        message('添加失败', $this->createWebUrl('project_add'));
    }
}

load()->web('tpl');
include $this->template('web/project_add');
?>
