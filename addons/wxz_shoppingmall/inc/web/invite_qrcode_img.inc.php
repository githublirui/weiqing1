<?php

/**
 * 邀请注册二维码
 */
global $_W, $_GPC;
require_once WXZ_SHOPPINGMALL . '/lib/phpqrcode/phpqrcode.php';
$url = "{$_W['siteroot']}app/index.php?i={$_W['uniacid']}&c=entry&do=reg_invite&m={$_GPC['m']}";
QRcode::png($url, false, QR_ECLEVEL_Q, 4);
?>
