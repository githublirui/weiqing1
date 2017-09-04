<?php
		global $_W, $_GPC;
		$uniacid = $_W['uniacid'];
		if(checksubmit()) {
			$dat['setting'] = array(
					'img_text'=>$_GPC['img_text'],
					'uniacid'=>$uniacid
			);
			
			$setting = pdo_get('hangyi_hbset', array('uniacid' => $uniacid));
			if(!$setting){
				$result = pdo_insert('hangyi_hbset', $dat['setting']);
			}else{
				$result = pdo_update('hangyi_hbset',$dat['setting'], array('uniacid' => $uniacid));
			}
		}
		$settings = pdo_get('hangyi_hbset', array('uniacid' =>$uniacid));
		include $this->template('sphbset');