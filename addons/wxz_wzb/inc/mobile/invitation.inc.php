<?php
global $_W,$_GPC;
require(IA_ROOT . '/framework/library/qrcode/phpqrcode.php');
$uid=$_GPC['wzbagent_users'.$_W['uniacid']];
$rid = intval($_GPC['rid']);
load()->func('communication');

$list = pdo_fetch("SELECT * FROM ".tablename('wzbagent_invitations')." WHERE uniacid=:uniacid AND rid=:rid",array(':uniacid'=>$_W['uniacid'],':rid'=>$rid));
$user = pdo_fetch('SELECT * FROM ' . tablename('wzbagent_users') . ' WHERE `uniacid` = :uniacid AND `id` = :uid', array(':uniacid' => $_W['uniacid'],':uid' => $uid) );

$setting = pdo_fetch('SELECT * FROM ' . tablename('wzbagent_lives') . ' WHERE `uniacid` = :uniacid and `rid` = :rid', array(':uniacid' => $_W['uniacid'],':rid' => $rid));

$B = ihttp_request(tomedia($user['headimgurl']));

$img = base64_encode(($B['content']));

$weekarray=array("日","一","二","三","四","五","六");


$link_url = $_W['siteroot'].str_replace('./','app/',$this->createMobileurl('index',array('share_uid' => $uid,'rid'=>$rid)));
$imgUrl = "../addons/wxz_wzbagent/qrcodes/wzbagent".$uid.$rid.".png";
load()->func('file');
mkdirs(MODULE_ROOT . '/qrcode');
$dir = $imgUrl;
$flag = file_exists($dir);
//生成二维码图片
$errorCorrectionLevel = "L";
$matrixPointSize = "4";
QRcode::png($link_url,$imgUrl,$errorCorrectionLevel,$matrixPointSize);
//生成二维码图片
include $this->template('invitation');
?>