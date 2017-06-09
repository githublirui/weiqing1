<?php

/*
 * 场景页面 
 * 
 */
require_once WXZ_PANORAMA . '/function/global.func.php';
require_once WXZ_PANORAMA . '/source/Scene.class.php';

global $_W, $_GPC;
$modulePath = '../addons/' . $_GPC['m'] . '/';

//获取所有全景
$Scenes = Scene::getAllScene();

if (!$Scenes) {
    message('暂未上传场景，请耐心等待', $this->createMobileUrl('index'));
}

$sid = $_GPC['sid'] ? $_GPC['sid'] : $Scenes[0]['id']; //默认第一个场景

$SceneInfo = Scene::getById($sid);
if (!$SceneInfo) {
    message('场景不存在，或已删除', $this->createMobileUrl('index'));
}

$audioPath = tomedia($SceneInfo['audio']);

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


include $this->template('scene/index');
?>
