<?php

/*
 *  
 * 录音测试页面
 */
global $_W, $_GPC;
$modulePath = '../addons/' . $_GPC['m'] . '/';
$type = $_GPC['t'];
include $this->template('test');
?>
