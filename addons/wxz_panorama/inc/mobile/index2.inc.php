<?php

/*
 *  
 * 立马抢购页面
 */
require_once WXZ_PANORAMA . '/function/global.func.php';
global $_W, $_GPC;
$modulePath = '../addons/' . $_GPC['m'] . '/';
$user = $this->auth();
unset($_SESSION['__:proxy:WXZ_PANORAMA_VIEW_SCENES']); //删除浏览场景记录
$_W['module_config'] = $this->module['config'];

include $this->template(get_real_tpl('index2'));
?>
