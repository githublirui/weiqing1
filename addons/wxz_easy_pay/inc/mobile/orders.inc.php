<?php

global $_GPC, $_W;
checkauth();
$uid = $_W['fans']['uid'];

//$uid = 0;
$sell_out = $_GPC['sell_out'];
if ($_GPC['ac'] == 'ajaxdele') {
    $hangyi_product = pdo_get('hangyi_product', array('id' => $_GPC['id'], 'uid' => $_W['fans']['uid']));
    if ($hangyi_product['id']) {
        $re = pdo_query("DELETE FROM " . tablename('hangyi_product') . "  WHERE id = '" . $hangyi_product['id'] . "' limit 1");
        if ($re) {
            echo 1;
        }
    }
    exit();
}

if ($sell_out) {
    $sql = 'SELECT * FROM ' . tablename('hangyi_product') . " WHERE uid = :uid and (`goodsStock` < 1  or `goodsStock`='' or `goodsStock` is NULL)order by id desc";
} else {
    $sql = 'SELECT * FROM ' . tablename('hangyi_product') . " WHERE uid = :uid and `goodsStock` > 0 order by id desc";
}

$pars[':uid'] = $uid;

$trades_p = pdo_fetchall($sql, $pars);
include $this->template('orders');
?>




