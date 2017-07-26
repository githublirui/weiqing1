<?php

global $_W, $_GPC;
require_once WXZ_PANORAMA . '/function/global.func.php';
$start_time = $this->module['config']['quanjin']["start_time"];
$end_time = $this->module['config']['quanjin']["end_time"];

if ($start_time && time() < strtotime($start_time)) {
    $show_msg = '<p>亲，活动即将开始</p><p>不要着急哦~~</p><p>活动时间' . date("m月-d日", strtotime($start_time)) . '-' . date("m月-d日", strtotime($end_time)) . '</p>';
    include $this->template(get_real_tpl('msg'));
    die;
}

if ($end_time && time() >= strtotime($end_time)) {
    $show_msg = '<p>亲，活动已经结束</p><p>期待下次参与哦~~</p><p>活动时间' . date("m月-d日", strtotime($start_time)) . '-' . date("m月-d日", strtotime($end_time)) . '</p>';
    include $this->template(get_real_tpl('msg'));
    die;
}

$user = $this->auth();

//判断是否关注
if ($_GPC['openid']) {
    $sub_openid = $_GPC['openid'];
} else {
    $sub_openid = $_SESSION['__:proxy:WXZ_PANORAMA_OPENID']; //订阅号的openid
}


if ($user) {
    $pars[':uniacid'] = $_W['uniacid'];
    $pars[':openid'] = $user['openid'];
    if ($sub_openid) {
        $pars[':openid'] = $sub_openid;
    }
    $sql = 'SELECT * FROM ' . tablename('mc_mapping_fans') . ' WHERE `uniacid`=:uniacid AND `openid`=:openid';
    $wx_fans = pdo_fetch($sql, $pars);
}

//判断是否关注订阅号
if (!$wx_fans || $wx_fans['follow'] != 1) {
    //没有关注
    if ($this->module['config']['force_follow'] == 1) {
        message('请先关注帐号后参加活动', $this->module['config']['force_follow_url']);
    }
}

require_once WXZ_PANORAMA . '/source/Page.class.php';
$pageContents = Page::getPage(array(4, 5, 6, 7, 8));
?>
