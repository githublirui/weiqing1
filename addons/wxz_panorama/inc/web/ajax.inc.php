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
    require_once WXZ_PANORAMA . '/source/UtilsFile.class.php';
    $id = $_GPC['scene_id'];
    $ret = pdo_delete('wxz_panorama_scene', array('id' => $id));
    //删除目录
    $modulePath = '../addons/' . $_GPC['m'] . '/';
    $attachdir = IA_ROOT . '/' . $_W['config']['upload']['attachdir'] . '/';
    $scene_path = "$modulePath/template/mobile/scene/{$_W['uniacid']}/vrpano{$id}";
    UtilsFile::deleteDirectory($scene_path);
    echo "ok";
    die;
}
?>
