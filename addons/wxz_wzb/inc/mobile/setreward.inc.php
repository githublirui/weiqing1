<?php
global $_W,$_GPC;
$openid = $_W['openid'];
$rid = intval($_GPC['rid']);
$type = intval($_GPC['type']);
$money = floatval($_GPC['money']);
$touid = intval($_GPC['touid']);
$tonickname = intval($_GPC['tonickname']);
$toheadurl = intval($_GPC['toheadurl']);
$uid=$_GPC['wxz_wzb_user'.$_W['uniacid']];
$isweixin = 1;
if($type==2){
	$money = $money*100;
}
load()->func('communication');
if(empty($rid)){
	$result = array('s'=>'-1','msg'=>'直播不存在','isweixin'=>$isweixin);
	echo json_encode($result);exit;
}


if(empty($money) || $money<1 ){
	$result = array('s'=>'-1','msg'=>'最少为0.01元哦！','isweixin'=>$isweixin);
	echo json_encode($result);exit;
}

if(!$uid || $uid==0){
	$result = array('s'=>'-1','msg'=>'用户不存在！','isweixin'=>$isweixin);
	echo json_encode($result);exit;
}

$user = pdo_fetch('SELECT * FROM ' . tablename('wxz_wzb_user') . ' WHERE `uniacid` = :uniacid AND `id` = :uid', array(':uniacid' => $_W['uniacid'],':uid' => $uid) );
if(!$user){
	$result = array('s'=>'-1','msg'=>'用户不存在！','isweixin'=>$isweixin);
	echo json_encode($result);exit;
}
if($touid > 0){
	$touser = pdo_fetch('SELECT * FROM ' . tablename('wxz_wzb_user') . ' WHERE `uniacid` = :uniacid AND `id` = :uid', array(':uniacid' => $_W['uniacid'],':uid' => $touid) );
	if(!$touser){
		$touser['nickname'] = $tonickname;
		$touser['headurl'] = $toheadurl;
	}
}

$datads=array(
	'uniacid'=>$_W['uniacid'],
	'uid'=>$uid,
	'fee'=>$money,
	'rid'=>$rid,
	'touid'=>$touid,
	'tonickname'=>$touser['nickname'],
	'toheadurl'=>$touser['headurl'],
	'dateline'=>time()
);

pdo_insert('wxz_wzb_ds', $datads);
$id=pdo_insertid();

$params = array(
	'fee' =>$money,
	'user' =>$_W['openid'],
	'title' =>urldecode($content),
	'random'=>random(16).$id,
);

$setting = uni_setting($_W['uniacid'], array('payment'));
if($setting['payment']['wechat']['switch']==2){
	$setting = uni_setting($setting['payment']['wechat']['borrow'], array('payment'));
}
if(!is_array($setting['payment']['wechat'])) {
	pdo_delete('wxz_wzb_comment',array('id'=>$id));
	$result = array('s'=>'-1','msg'=>'信息出错！','isweixin'=>$isweixin);
	echo json_encode($result);exit;
}

$wechat = $setting['payment']['wechat'];

$sql = 'SELECT `key`,`secret` FROM ' . tablename('account_wechats') . ' WHERE `acid`=:acid';
$row = pdo_fetch($sql, array(':acid' => $wechat['account']));
$wechat['appid'] = $row['key'];
$wechat['secret'] = $row['secret'];

if ($wechat['version'] == 1) {
	$option = array();
	$option['appId'] = $wechat['appid'];
	$option['timeStamp'] = time();
	$option['nonceStr'] = random(8);
	$package = array();
	$package['bank_type'] = 'WX';
	$package['body'] = '打赏';
	$package['attach'] = $_W['uniacid'];
	$package['partner'] = $wechat['partner'];
	$package['out_trade_no'] = $params['random'];
	$package['total_fee'] = $params['fee'];
	$package['fee_type'] = '1';
	$package['notify_url'] = $_W['siteroot'] . 'addons/wxz_wzb/reward.php';
	$package['spbill_create_ip'] = CLIENT_IP;
	$package['time_start'] = date('YmdHis', time());
	$package['time_expire'] = date('YmdHis', time() + 600);
	$package['input_charset'] = 'UTF-8';
	ksort($package);
	$string1 = '';
	foreach($package as $key => $v) {
		if (empty($v)) {
			continue;
		}
		$string1 .= "{$key}={$v}&";
	}
	$string1 .= "key={$wechat['key']}";
	$sign = strtoupper(md5($string1));

	$string2 = '';
	foreach($package as $key => $v) {
		$v = urlencode($v);
		$string2 .= "{$key}={$v}&";
	}
	$string2 .= "sign={$sign}";
	$option['package'] = $string2;

	$string = '';
	$keys = array('appId', 'timeStamp', 'nonceStr', 'package', 'appKey');
	sort($keys);
	foreach($keys as $key) {
		$v = $option[$key];
		if($key == 'appKey') {
			$v = $wechat['signkey'];
		}
		$key = strtolower($key);
		$string .= "{$key}={$v}&";
	}
	$string = rtrim($string, '&');

	$option['signType'] = 'SHA1';
	$option['paySign'] = sha1($string);
	if (is_error($option)) {
		pdo_delete('wxz_wzb_comment',array('id'=>$id));
		$result = array('s'=>'-1','msg'=>'信息出错！','isweixin'=>$isweixin);
		echo json_encode($result);exit;
	}
	$result = array('s'=>'-1','msg'=>$option,'isweixin'=>$isweixin);
	

} else {

	$package = array();
	$package['appid'] = $wechat['appid'];
	$package['mch_id'] = $wechat['mchid'];
	$package['nonce_str'] = random(8);
	$package['body'] = '打赏';
	$package['attach'] = $_W['uniacid'];
	$package['out_trade_no'] = $params['random'];
	$package['total_fee'] = $params['fee'];
	$package['spbill_create_ip'] = CLIENT_IP;
	$package['time_start'] = date('YmdHis', time());
	$package['time_expire'] = date('YmdHis', time() + 600);
	$package['notify_url'] = $_W['siteroot'] . 'addons/wxz_wzb/reward.php';
	$package['trade_type'] = 'JSAPI';
	$package['openid'] = $user['openid'];

	ksort($package, SORT_STRING);
	$string1 = '';
	foreach($package as $key => $v) {
		if (empty($v)) {
			continue;
		}
		$string1 .= "{$key}={$v}&";
	}
	$string1 .= "key={$wechat['signkey']}";
	$package['sign'] = strtoupper(md5($string1));
	$dat = array2xml($package);
	$response = ihttp_request('https://api.mch.weixin.qq.com/pay/unifiedorder', $dat);
	
	if (is_error($response)) {
		$result = array('s'=>'-1','msg'=>strval($xml->return_msg),'isweixin'=>$isweixin);
	
		echo json_encode($result);exit;
		return $response;
	}
	
	$xml = @isimplexml_load_string($response['content'], 'SimpleXMLElement', LIBXML_NOCDATA);
			
	if (strval($xml->return_code) == 'FAIL') {
		$result = array('s'=>'-1','msg'=>strval($xml->return_msg),'isweixin'=>$isweixin);
	
		echo json_encode($result);exit;

	}

	if (strval($xml->result_code) == 'FAIL') {
		$result = array('s'=>'-1','msg'=>strval($xml->return_msg),'isweixin'=>$isweixin);
	
		echo json_encode($result);exit;

	}
	$prepayid = $xml->prepay_id;
	$option['appId'] = $wechat['appid'];
	$option['timeStamp'] = TIMESTAMP;
	$option['nonceStr'] = random(8);
	$option['package'] = 'prepay_id='.$prepayid;
	$option['signType'] = 'MD5';
	ksort($option, SORT_STRING);
	foreach($option as $key => $v) {
		$string .= "{$key}={$v}&";
	}
	$string .= "key={$wechat['signkey']}";
	$option['paySign'] = strtoupper(md5($string));
	$option['status'] = 1;
	$option['res'] = 'ok';
	
	$result = array('s'=>'1','msg'=>$option,'isweixin'=>$isweixin);
}
if($touid>0){
	$user = pdo_fetch('SELECT * FROM ' . tablename('wxz_wzb_user') . ' WHERE `uniacid` = :uniacid AND `id` = :uid', array(':uniacid' => $_W['uniacid'],':uid' => $uid) );
	$result['content'] = $user['nickname'].'给'.$touser['nickname'].'打赏了1个<span>红包</span>';
}else{
	$result['content'] = $user['nickname'].'给主播打赏了1个<span>红包</span>';
}
$result['id'] = $id;
$result['type'] = 'reward';
echo json_encode($result);exit;
?>