<?php
global $_W, $_GPC;
$uniacid = $_W['uniacid'];
if(checksubmit()) {
			
			$dat['setting'] = array(
					'my_rate'=>$_GPC['my_rate'],
					'uniacid'=>$uniacid
			);
		
			$setting = pdo_get('hangyi_my_rate', array('uniacid' => $uniacid));
			if(!$setting){
				$result = pdo_insert('hangyi_my_rate', $dat['setting']);
			}else{
				$result = pdo_update('hangyi_my_rate',$dat['setting'], array('uniacid' => $uniacid));
			}
}
$setting = pdo_get('hangyi_my_rate', array('uniacid' => $uniacid));


include $this->template('my_rate');