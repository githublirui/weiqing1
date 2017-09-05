<?php

global $_W, $_GPC;
$do = (string) trim($_GPC['sdo']);
$_W['module_setting'] = $this->module['config'];

if ($do == 'del_page') {
    $id = $_GPC['id'];
    $update_sql = "UPDATE " . tablename("wxz_easy_pay_page") . " set title='',`desc`='',img='',link='' where id={$id}";
    $ret = pdo_query($update_sql);
    echo "ok";
    die;
}
?>
