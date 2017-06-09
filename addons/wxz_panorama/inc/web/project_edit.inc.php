<?php

global $_W, $_GPC;

$id = $_GPC['id'];
$project_info_sql = "SELECT * FROM " . tablename('wxz_panorama_project') . " WHERE id={$id}";
$project_info = pdo_fetch($project_info_sql);

if (!$project_info) {
    message('项目不存在', $this->createWebUrl('project_list'));
}

load()->web('tpl');
if (checksubmit()) {
    $data = array(
        'name' => $_GPC['name'],
        'update_at' => time()
    );

    if (pdo_update('wxz_panorama_project', $data, array('id' => $id))) {
        message('更新成功', $this->createWebUrl('project_list'));
    } else {
        message('更新失败', $this->createWebUrl('project_add'));
    }
}
include $this->template('web/project_edit');
?>
