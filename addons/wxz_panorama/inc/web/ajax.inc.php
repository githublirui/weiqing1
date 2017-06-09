<?php

global $_W, $_GPC;
$do = (string) trim($_GPC['sdo']);
$_W['module_setting'] = $this->module['config'];

if ($do == 'del_page') {
    $id = $_GPC['id'];
    $update_sql = "UPDATE " . tablename("wxz_panorama_page") . " set `isdel`=1 where id={$id}";
    $ret = pdo_query($update_sql);
    echo "ok";
    die;
} else if ($do == 'del_award') {
    $id = $_GPC['award_id'];
    $ret = pdo_delete('wxz_panorama_award', array('id' => $id));
    echo "ok";
    die;
} else if ($do == 'del_scene') {
    require_once WXZ_PANORAMA . '/source/Scene.class.php';
    $id = (int) $_GPC['scene_id'];
    Scene::delById($id);
    echo "ok";
    die;
} else if ($do == 'del_project') {
    require_once WXZ_PANORAMA . '/source/Project.class.php';
    $id = $_GPC['id'];
    Project::delById($id);
    echo "ok";
    die;
}
?>
