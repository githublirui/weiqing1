<?php
global $_W, $_GPC;
if($_GPC['ajaxac']==1){
	$id = (int)$_GPC['id'];
	if($id){
		$row = pdo_query("DELETE FROM ".tablename('hangyi_product')." WHERE id = :id", array(':id' => $id));
		echo $row;
	}else{
		echo '';
	}
	
	exit();
}
if ($_GPC['showorder']==2 || !$_GPC['showorder']) {
    $showorder = " order by `id` desc";
}else if($_GPC['showorder']==1){
	$showorder = " order by `sell_num` desc";
}


$pindex = intval($_GPC['page']);
$pindex = max($pindex, 1);
$psize = 15;

$start = ($pindex - 1) * $psize;

$pars = array();
$pars[':uniacid'] = $_W['uniacid'];

$sql = "SELECT count(*) as num FROM " . tablename('hangyi_product') . $showorder;
$total = pdo_fetchcolumn($sql, $pars);

$sql = "SELECT * FROM " . tablename('hangyi_product') . $showorder."  limit $start , $psize";
$list = pdo_fetchall($sql, $pars);
$pager = pagination($total, $pindex, $psize);
include $this->template('productmanage');