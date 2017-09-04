<?php
//print_R($_FILES);
global $_GPC, $_W;
checkauth();
if($_W['fans']){
	
}else{
	exit();
}
if(empty($_GPC['oid'])){
	exit();
}
$oid = (int)$_GPC['oid'];
$orderinfo = pdo_get('hangyi_order', array('id' => $oid,'sell_openid'=>$_W['fans']['openid']));
if($orderinfo){
	$buy_info = pdo_get('hangyi_user', array('uid' => $orderinfo['buy_id']));
	$product_info = pdo_get('hangyi_product', array('id' => $orderinfo['pid']));
}else{
	exit();
}

include $this->template('orderdetail');

?>




