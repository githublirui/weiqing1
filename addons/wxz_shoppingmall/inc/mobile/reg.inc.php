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

session_start();
$code = $_SESSION['wxz_shoppingmall_reg_invite_code'];

//是否有邀请码
if (!$code) {
    message('请先输入邀请码', $this->createMobileUrl('regInvite'));
}

$sql = "SELECT isuse FROM " . tablename('wxz_shoppingmall_invite_code') . " WHERE uniacid='{$_W['uniacid']}' AND `code`='{$code}'";
$codeInfo = pdo_fetch($sql);

if (!$codeInfo) {
    message('邀请码不存在，请重新输入', $this->createMobileUrl('reg_invite'));
}
if ($codeInfo['isuse'] == 2) {
    message('邀请码已使用', $this->createMobileUrl('reg_invite'));
}

require_once WXZ_SHOPPINGMALL . '/source/Page.class.php';
$pageContents = Page::getPage(array(7));

include $this->template('reg');
?>

