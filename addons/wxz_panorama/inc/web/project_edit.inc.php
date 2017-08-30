<?php

global $_W, $_GPC;

require_once WXZ_PANORAMA . '/source/Project.class.php';
require_once WXZ_PANORAMA . '/source/Activity.class.php';
$activitys = Activity::getAll('id,name');

$id = $_GPC['id'];
$project_info = Project::getById($id);
$project_info['url'] = $_W['siteroot'] . "app/index.php?i=" . $_W['uniacid'] . "&pid=" . $pid . "&c=entry&do=quanjing&m=" . $_GPC['m'] . '&pid=' . $id . "aid={$project_info['aid']}";
if (!$project_info) {
    message('项目不存在', $this->createWebUrl('activity_list'));
}

load()->web('tpl');
if (checksubmit()) {
    $data = array(
        'aid' => (int) $_GPC['aid'],
        'name' => $_GPC['name'],
        'update_at' => time()
    );

    if (pdo_update('wxz_panorama_project', $data, array('id' => $id))) {
        message('更新成功', $this->createWebUrl('project_list', array('aid' => $project_info['aid'])));
    } else {
        message('更新失败', $this->createWebUrl('project_edit', array('id' => $id)));
    }
}
include $this->template('web/project_edit');
?>
