<?php

/**
 * 签到功能
 */
global $_W, $_GPC;
include dirname(__FILE__) . '/permission.php';

$modulePublic = '../addons/' . $_GPC['m'] . '/static/';

include $this->template('signin');
?>
