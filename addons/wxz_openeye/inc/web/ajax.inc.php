<?php

global $_W, $_GPC;
$do = (string) trim($_GPC['sdo']);
$_W['module_setting'] = $this->module['config'];
if ($do == 'del_fans') {
    $id = $_GPC['id'];
    $del = pdo_delete('wxz_openeye_fans', array('uid' => $id));
    echo "ok";
    die;
} else if ($do == 'set_admin_fans') {
    $id = $_GPC['id'];
    $isCheck = $_GPC['is_check'];
    $uodateData = [
        'is_check' => $isCheck,
    ];
    if ($isCheck == 2) {
        $uodateData['is_admin'] = 1;
    }
    pdo_update('wxz_openeye_fans', $uodateData, array('uid' => $id));
}
?>
