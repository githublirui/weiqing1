<?php

/*
 * 场景页面 
 * 
 */
require_once WXZ_PANORAMA . '/function/global.func.php';
global $_W, $_GPC;
$modulePath = '../addons/' . $_GPC['m'] . '/';
$pano = $_GPC['pno'] ? $_GPC['pno'] : 1;

$scene_path = "$modulePath/template/mobile/scene/{$_GPC['i']}";
$pano_count = getfilecounts($scene_path);
sort($pano_count);
$pano = $pano_count <= 1 ? 1 : $pano;

if (!isset($pano_count[$pano - 1])) {
    $pano = 1;
}
$panoFolder = $pano_count[$pano - 1];

$siteroot_encode = urlencode($_W['siteroot']);

$redirect = "{$_W['siteroot']}app/index.php?i={$_GPC['i']}&c=entry&do=index&m={$_GPC['m']}";
$user = $this->auth();
include dirname(__FILE__) . '/permission.php';

//判断是否中奖，分享
$sql = "select * from " . tablename('wxz_panorama_win') . " where fans_id =" . $user["uid"];
$is_win = pdo_fetch($sql, $pars);
$sql = "select share_num,cellphone from " . tablename('wxz_panorama_fans') . " where uid =" . $user["uid"];
$is_fans = pdo_fetch($sql, $pars);


if ($is_win && $is_fans['share_num'] == '0' && $is_fans['cellphone'] == '') {
    include $this->template(get_real_tpl('input_msg'));
    die();
}

if ($is_win && $is_fans['share_num'] == '0' && $is_fans['cellphone']) {
    $show_msg = "<p>恭喜你，获得了</p><p id='black'>" . $is_win['award'] . "</p><p>分享后可以领取，实物奖品需到售楼处领取</p>";
    include $this->template(get_real_tpl('msg'));
    die();
}

//if ($is_win && $is_fans['share_num'] >= '1' && $is_fans['cellphone']) {
//    $show_msg = "<p>恭喜你，已经获得了</p><p id='black'>" . $is_win['award'] . "</p><p>（您奖品ID为：" . $is_win['award_id'] . " ）</p>";
//    include $this->template(get_real_tpl('msg'));
//    die();
//}

include $this->template('scene/' . $_GPC['i'] . '/' . $panoFolder . '/index');
?>
