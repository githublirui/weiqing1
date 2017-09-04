<?php
global $_W, $_GPC;
$uniacid = $_W['uniacid'];
if(checksubmit()) {
			
			$dat['setting'] = array(
					'add_one'=>$_GPC['add_one'],
					'add_two'=>$_GPC['add_two'],
					'add_three'=>$_GPC['add_three'],
					'uniacid'=>$uniacid
			);
		
			$setting = pdo_get('hangyi_add_font', array('uniacid' => $uniacid));
			if(!$setting){
				$result = pdo_insert('hangyi_add_font', $dat['setting']);
			}else{
				$result = pdo_update('hangyi_add_font',$dat['setting'], array('uniacid' => $uniacid));
			}
}
$setting = pdo_get('hangyi_add_font', array('uniacid' => $uniacid));


include $this->template('add_font');