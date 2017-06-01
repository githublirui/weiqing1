<?php

/*
 * 
 * 首页
 */
require_once WXZ_PANORAMA . '/function/global.func.php';
require_once WXZ_PANORAMA . '/source/Page.class.php';
global $_W, $_GPC;
$modulePath = '../addons/' . $_GPC['m'] . '/';
unset($_SESSION['__:proxy:WXZ_PANORAMA_VIEW_SCENES']); //删除浏览场景记录
//获取图片域名
setting_load('remote');
if ($_W['setting']['remote']['type']) {
    $attach_url = $_W['attachurl_remote'];
} else {
    $attach_url = $_W['siteroot'] . $_W['config']['upload']['attachdir'];
}

$user = $this->auth();

$pageContents = Page::getPage(array(1, 2, 3));

include $this->template(get_real_tpl('index'));
?>
