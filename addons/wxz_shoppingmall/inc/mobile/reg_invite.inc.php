<?php

/**
 * 用户输入邀请码
 */
global $_W, $_GPC;
include dirname(__FILE__) . '/permission.php';
$modulePublic = '../addons/' . $_GPC['m'] . '/static/';

include $this->template('regInvite');
?>

