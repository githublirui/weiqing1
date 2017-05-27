<?php

/*
 * 奖品页面
 * 
 */
include dirname(__FILE__) . '/permission.php';
global $_W, $_GPC;
$_W['module_config'] = $this->module['config'];

$modulePath = '../addons/' . $_GPC['m'] . '/';
unset($_SESSION['__:proxy:WXZ_PANORAMA_VIEW_SCENES']); //删除浏览场景记录
$user = $this->auth();
//活动开始结束判断
//写入session判断是否关注
if ($_GPC['openid']) {
    $quanjing_openid = $_GPC['openid'];
    $_SESSION['__:proxy:WXZ_PANORAMA_OPENID'] = $quanjing_openid;
}
//判断是否中奖，分享
$sql = "select * from " . tablename('wxz_panorama_win') . " where fans_id =" . $user["uid"];
$is_win = pdo_fetch($sql, $pars);
$sql = "select share_num,cellphone from " . tablename('wxz_panorama_fans') . " where uid =" . $user["uid"];
$is_fans = pdo_fetch($sql, $pars);
if ($is_win && $is_fans['share_num'] == '0' && $is_fans['cellphone']) {
    $show_msg = "<p>恭喜你，获得了</p><p id='black'>" . $is_win['award'] . "</p><p>分享后可以领取</p>";
}
if ($is_win && $is_fans['share_num'] > '0' && $is_fans['cellphone']) {
    $show_msg = "<p>恭喜你，已经获得了</p><p id='black'>" . $is_win['award'] . "</p><p>（您奖品ID为：" . $is_win['award_id'] . " ）</p>";
}

if (!$is_win) {
    $show_msg = "<p>【 亲，你还没有中奖哦，快去全景里找宝箱吧。（<a href='{$_W['siteroot']}app/index.php?i={$_GPC['i']}&c=entry&do=quanjing&m={$_GPC['m']}'>进入全景寻宝</a>）】</p>";
}
$redirect = "";
include $this->template(get_real_tpl('msg'));
?>
