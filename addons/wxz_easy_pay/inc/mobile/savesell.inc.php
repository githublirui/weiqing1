<?php
//print_R($_FILES);
global $_GPC, $_W;
checkauth();
if($_W['fans']){
	
}else{
	exit();
}
$uid = $_W['fans']['uid'];
$dat['set'] = array(
					'openid'=>$_W['fans']['openid'],
					'add_time'=>time(),
					'nickname'=>$_W['fans']['nickname'],
					'headimgurl'=>$_W['fans']['headimgurl'],
					'uid'=>$_W['fans']['uid']
			);
			$sellinfo = pdo_get('hangyi_user', array('uid' => $_W['fans']['uid']));
			
			if(!$sellinfo){
				$result = pdo_insert('hangyi_user', $dat['set']);
			}					

if($_GPC['ac']=='ajaxsend'){
		$sql = "UPDATE ".tablename('hangyi_user')." SET `tel`='".$_GPC['phone']."',`weixin`='".$_GPC['weixin']."',`mysaddress`='".$_GPC['weui_textarea']."' WHERE uid = '".$_W['fans']['uid']."' limit 1";
		$re = pdo_query($sql);
		if($re){
			echo 1;
		}
	exit();
}
include $this->template('savesell');

?>




