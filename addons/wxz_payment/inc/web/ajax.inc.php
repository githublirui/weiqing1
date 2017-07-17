<?php

global $_W, $_GPC;
$do = (string) trim($_GPC['sdo']);
$_W['module_setting'] = $this->module['config'];
if ($do == 'del_fans') {
    $id = $_GPC['id'];
    $del = pdo_delete('wxz_payment_fans', array('uid' => $id));
    echo "ok";
    die;
}
?>
