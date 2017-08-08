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
    $uodateData = array(
        'is_check' => $isCheck,
    );
    if ($isCheck == 2) {
        $uodateData['is_admin'] = 1;
    }
    pdo_update('wxz_openeye_fans', $uodateData, array('uid' => $id));
} elseif ($do == 'save_page_order') {
    //保存商铺排序
    $ids = $_GPC['ids'];
    $orders = $_GPC['orders'];

    foreach ($ids as $k => $id) {
        $data = array(
            'order' => $orders[$k],
        );
        pdo_update('wxz_openeye_page', $data, array('id' => $id));
    }
    echo 'ok';
    die;
}
?>
