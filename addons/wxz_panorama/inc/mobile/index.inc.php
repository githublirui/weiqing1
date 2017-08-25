<?php

/*
 * 
 * 首页
 */
require_once WXZ_PANORAMA . '/function/global.func.php';
require_once WXZ_PANORAMA . '/source/Page.class.php';
require_once WXZ_PANORAMA . '/source/Activity.class.php';

global $_W, $_GPC;
$modulePath = '../addons/' . $_GPC['m'] . '/';
$_W['module_config'] = $this->module['config'];

$aid = intval($_GPC['aid']);

$activityInfo = Activity::getById($aid, 'id,name');

if (!$aid) {
    message('活动参数错误', $this->createMobileUrl('index'));
}

//获取图片域名
setting_load('remote');
if ($_W['setting']['remote']['type']) {
    $attach_url = $_W['attachurl_remote'];
} else {
    $attach_url = $_W['siteroot'] . $_W['config']['upload']['attachdir'];
}

$user = $this->auth();

$pageContents = Page::getPage($aid, array(1, 2, 3));

include $this->template(get_real_tpl('index'));
?>
