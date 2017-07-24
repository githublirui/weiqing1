<?php

global $_GPC;
include IA_ROOT . '/framework/library/qrcode/phpqrcode.php';
$url = urldecode($_GPC['url']);
QRcode::png($url, false, QR_ECLEVEL_Q, 4);

?>