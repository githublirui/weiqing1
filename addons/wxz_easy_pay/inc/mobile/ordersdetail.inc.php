<?php

global $_GPC, $_W;
checkauth();
if ($_W['fans']) {
    
} else {
    message('用户授权错误', $this->createMobileUrl('my'));
}


if (empty($_GPC['oid'])) {
    message('订单不存在', $this->createMobileUrl('my'));
}

$oid = (int) $_GPC['oid'];
$orderinfo = pdo_get('hangyi_order', array('id' => $oid, 'sell_openid' => $_W['fans']['openid']));
if ($orderinfo) {
    $buy_info = pdo_get('hangyi_user', array('uid' => $orderinfo['buy_id']));
    $product_info = pdo_get('hangyi_product', array('id' => $orderinfo['pid']));
    
    $productIds = explode(',', $orderinfo['pids']);
    $goodnums = explode(',', $orderinfo['goodsNums']);
    $attrs = explode(',', $orderinfo['attrs']);
    
    foreach($productIds as $productId) {
        $product_infos[]  = pdo_get('hangyi_product', array('id' => $productId));
    }
    
} else {
    message('订单不存在', $this->createMobileUrl('my'));
}



include $this->template('orderdetail');
?>




