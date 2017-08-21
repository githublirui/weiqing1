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
} else if ($do == 'del_activity') {
    require_once WXZ_PANORAMA . '/source/Activity.class.php';
    $id = $_GPC['id'];
    Activity::delById($id);
    echo "ok";
    die;
} else if ($do == 'del_project') {
    require_once WXZ_PANORAMA . '/source/Project.class.php';
    $id = $_GPC['id'];
    Project::delById($id);
    echo "ok";
    die;
} else if ($do == 'del_hotspot') {
    require_once WXZ_PANORAMA . '/source/Hotspot.class.php';
    $id = $_GPC['id'];
    Hotspot::delById($id);
    echo "ok";
    die;
} elseif ($do == 'save_project_order') {
    //保存商铺排序
    $ids = $_GPC['ids'];
    $orders = $_GPC['orders'];

    foreach ($ids as $k => $id) {
        $data = array(
            'sort_order' => $orders[$k],
        );
        require_once WXZ_PANORAMA . '/source/Project.class.php';
        Project::updateById($id, $data);
    }
    echo 'ok';
    die;
}
?>
