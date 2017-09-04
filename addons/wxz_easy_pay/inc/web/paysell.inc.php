<?php

global $_W, $_GPC;
$uniacid = $_W['uniacid'];
if($_W['ispost'] && $_W['isajax']) {
	$paysell_isauto = (int)$_GPC['rids'];
	if($paysell_isauto !=1){
		$paysell_isauto =0;
	}
	$dat['setting'] = array(
		'paysell_isauto'=>$paysell_isauto,
		'uniacid'=>$uniacid
	);
			
		
			$setting = pdo_get('hangyi_peizhi', array('uniacid' => $uniacid));
			if(!$setting){
				$result = pdo_insert('hangyi_peizhi', $dat['setting']);
			}else{
				$result = pdo_update('hangyi_peizhi',$dat['setting'], array('uniacid' => $uniacid));
			}
	exit();
}

$setting = pdo_get('hangyi_peizhi', array('uniacid' => $uniacid));

$switch = $setting['paysell_isauto'] ? ' checked="checked"' : '';
include $this->template('paysell');