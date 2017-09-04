<?php
//print_R($_FILES);
global $_GPC, $_W;

if(checkauth()){
	echo json_encode(array("resultCode"=>"SUCCESS"));
}

