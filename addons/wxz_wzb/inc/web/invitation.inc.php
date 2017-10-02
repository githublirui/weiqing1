<?php

global $_W,$_GPC;
$uniacid = $_W['uniacid'];
$rid = $_GPC['rid'];

load()->func('tpl');

$list = pdo_fetch("SELECT * FROM ".tablename('wxz_wzb_invitation')." WHERE uniacid=:uniacid AND rid=:rid",array(':uniacid'=>$uniacid,':rid'=>$rid));

if ($_POST) {
	load()->func('file');
	$file = file_upload($_FILES['bg']);
	$fullname = ATTACHMENT_ROOT .  $file['path'];

	if ((($_FILES["bg"]["type"] == "image/gif") || ($_FILES["bg"]["type"] == "image/jpeg") || ($_FILES["bg"]["type"] == "image/png"))){
		if ($_FILES["bg"]["error"] > 0){
			message('图片太大',$this->createWebUrl('invitation',array('rid'=>$rid)),'error');
		} else {
			move_uploaded_file($_FILES["bg"]["tmp_name"], $fullname);
		}
	}else{
		message('格式不对',$this->createWebUrl('invitation',array('rid'=>$rid)),'error');
	}
    $data = array();
    $data['rid'] = $rid;
    $data['uniacid'] = $uniacid;
    $data['bg'] = $file['path'];
    $data['desc1'] = $_GPC['desc1'];
    $data['desc2'] = $_GPC['desc2'];
    $data['desc3'] = $_GPC['desc3'];
    $data['desc4'] = $_GPC['desc4'];

    if(!empty($list)){
        pdo_update('wxz_wzb_invitation',$data,array('rid'=>$rid,'uniacid'=>$uniacid));
        message('编辑成功',referer(),'success');
    }else{
        pdo_insert('wxz_wzb_invitation',$data);
        message('新增成功',$this->createWebUrl('invitation',array('rid'=>$rid)),'success');
    }

}

include $this->template('invitation');
?>