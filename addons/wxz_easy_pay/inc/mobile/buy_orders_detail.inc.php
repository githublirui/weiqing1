<?php
//print_R($_FILES);
global $_GPC, $_W;
checkauth();
if ($_W['fans']) {
    
} else {
    exit();
}
$uid = $_W['fans']['uid'];
$oid = $_GPC['id'];
$orderinfo = pdo_get('hangyi_order', array('id' => $oid, 'buy_id' => $_W['fans']['uid']));
if ($orderinfo) {
    $sell_info = pdo_get('hangyi_user', array('uid' => $orderinfo['sell_id']));
    $buy_info = pdo_get('hangyi_user', array('id' => $_W['fans']['uid']));
    $produt_info = pdo_get('hangyi_product', array('id' => $orderinfo['pid']));
}
include $this->template('buy_orders_detail');
?>




