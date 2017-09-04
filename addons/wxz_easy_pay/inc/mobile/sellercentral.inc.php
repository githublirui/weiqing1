<?php

		global $_GPC, $_W;
		load()->model('mc');
		$fan = mc_fansinfo($_W['openid']);
		if (!empty($fan) && !empty($fan['openid'])) {
			$userinfo = $fan;
		}else{
			$userinfo = mc_oauth_userinfo();
		}
		$uniacid = $_W['uniacid'];
		$setting_font = pdo_get('hangyi_add_font', array('uniacid' => $uniacid));
		if($_W['fans']){
			$fans = $_W['fans'];
			
			$openid = $fans['openid'];
			$dat['set'] = array(
					'openid'=>$userinfo['openid'],
					'add_time'=>time(),
					'nickname'=>$userinfo['nickname'],
					'headimgurl'=>$userinfo['headimgurl'],
					'uid'=>$userinfo['uid']
			);
			$userinfo = pdo_get('hangyi_user', array('openid' => $openid));
			$setting = pdo_get('hangyi_peizhi', array('uniacid' => $uniacid));
			if(!$userinfo){
			$result = pdo_insert('hangyi_user', $dat['set']);
			}					
								//$id = pdo_insertid();
			include $this->template('release_goods');
		}
		
		exit();
		
		