<?php

//print_R($_FILES);
global $_GPC, $_W;
load()->model('mc');
$fan = mc_fansinfo($_W['openid']);
if (!empty($fan) && !empty($fan['openid'])) {
    $userinfo = $fan;
} else {
    $userinfo = mc_oauth_userinfo();
}

require_once WXZ_EASY_PAY . '/source/Page.class.php';
$pageInfo = Page::getPage(array(14));

$pid = (int) $_GPC['pid'];

$product = pdo_get('hangyi_product', array('id' => $pid));

$url2 = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/app" . $this->createMobileUrl('ordershow') . "&orderid=" . $pid;
$url2 = str_replace("/app./index.php", "", $url2);

$url3 = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
$url3 = str_replace("app/index.php", "", $url3);
if ($product) {
    $goodsImg = $url3 . $product["goodsImg"];
    $goodsImg = str_replace("/../", "/", $goodsImg);
    include $this->template('ordershow');
}
?>


