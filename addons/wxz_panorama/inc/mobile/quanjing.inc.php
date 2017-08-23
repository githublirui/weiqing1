<?php

/*
 * 场景页面 
 * 
 */
require_once WXZ_PANORAMA . '/function/global.func.php';
require_once WXZ_PANORAMA . '/source/Scene.class.php';
require_once WXZ_PANORAMA . '/source/Project.class.php';

global $_W, $_GPC;
$modulePath = '../addons/' . $_GPC['m'] . '/';
$_W['module_config'] = $this->module['config'];

$aid = intval($_GPC['aid']); //活动id
if (!$aid) {
    message('活动参数错误', $this->createMobileUrl('index'));
}

$activityInfo = Activity::getById($aid);

$pid = $_GPC['pid']; //默认第一个项目
if ($pid) {
    $projectInfo = Project::getById($pid, 'id');
} else {
    $projectInfo = Project::getFirstPro($aid);
}

if (!$projectInfo) {
    message('场景不存在，或已删除', $this->createMobileUrl('index'));
}
$pid = $projectInfo['id'];
$user = $this->auth();
include dirname(__FILE__) . '/permission.php';

//判断是否中奖，分享
$sql = "select * from " . tablename('wxz_panorama_win') . " where fans_id =" . $user["uid"];
$is_win = pdo_fetch($sql, $pars);
$sql = "select share_num,cellphone from " . tablename('wxz_panorama_fans') . " where uid =" . $user["uid"];
$is_fans = pdo_fetch($sql, $pars);

//if ($is_win && $is_fans['share_num'] == '0' && $is_fans['cellphone'] == '') {
//    include $this->template(get_real_tpl('input_msg'));
//    die();
//}
//if ($is_win && $is_fans['share_num'] == '0' && $is_fans['cellphone']) {
//    $show_msg = "<p>恭喜你，获得了</p><p id='black'>" . $is_win['award'] . "</p><p>分享后可以领取，实物奖品需到售楼处领取</p>";
//    include $this->template(get_real_tpl('msg'));
//    die();
//}

include $this->template('scene/index');
?>
