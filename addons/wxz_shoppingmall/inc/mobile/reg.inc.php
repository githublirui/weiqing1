<?php

/**
 * 用户注册
 */
global $_W, $_GPC;
include dirname(__FILE__) . '/permission.php';
$modulePublic = '../addons/' . $_GPC['m'] . '/static/';
if ($user['mobile'] && !$user['birthday']) {
    header('Location: ' . $this->createMobileUrl('reg2'));
    exit;
}
if ($user['mobile']) {
    header('Location: ' . $this->createMobileUrl('index'));
    exit;
}

//是否有邀请码
if (!$_GPC['code']) {
    message('邀请码错误', $this->createMobileUrl('index'));
}

$sql = "SELECT isuse FROM " . tablename('wxz_shoppingmall_invite_code') . " WHERE uniacid='{$_W['uniacid']}' AND `code`='{$_GPC['code']}'";
$codeInfo = pdo_fetch($sql);

if (!$codeInfo) {
    message('邀请码错误', $this->createMobileUrl('index'));
}
if ($codeInfo['isuse'] == 2) {
    message('邀请码已使用', $this->createMobileUrl('index'));
}

session_start();
$_SESSION['wxz_shoppingmall_reg_invite_code'] = $_GPC['code'];
require_once WXZ_SHOPPINGMALL . '/source/Page.class.php';
$pageContents = Page::getPage(array(7));

include $this->template('reg');
?>

