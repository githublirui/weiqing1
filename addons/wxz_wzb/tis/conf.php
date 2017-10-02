<?php
class ADConf {
	const	AccessId = "701788891556";		//把...替换成自己的AccessId
	const	AccessKey = "fg52XaXMz0lml442uC4h8ZsToQLB9HFg";		//把...替换成自己的AccessKey
	const 	TisId = "7d32e8dbc6aea35331534ecfe339b1bd";			//把...替换成自己的TisId
}
$accessId = ADConf::AccessId;
$accessKey = ADConf::AccessKey;
if(!empty($_REQUEST["tisId"])){
	$tisId = $_REQUEST["tisId"];
} else {
	$tisId = ADConf::TisId;
}
?>