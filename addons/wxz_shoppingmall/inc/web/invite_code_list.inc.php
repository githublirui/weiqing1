<?php
/**
 * 邀请二维码列表
 */
global $_W, $_GPC;

$pindex = intval($_GPC['page']);
$pindex = max($pindex, 1);
$psize = 15;

require_once WXZ_SHOPPINGMALL . '/source/Fans.class.php';
$_W['module_setting'] = $this->module['config'];

$start = ($pindex - 1) * $psize;
$condition = "`uniacid` = {$_W['uniacid']}";

if ($_GPC['code']) {
    $condition .= " AND code='{$_GPC['code']}'";
}

if ($_GPC['isuse']) {
    $condition .= " AND isuse='{$_GPC['isuse']}'";
}


$sql = "SELECT count(*) as num FROM " . tablename('wxz_shoppingmall_invite_code') . " WHERE {$condition}";
$total = pdo_fetchcolumn($sql);

$sql = "SELECT * FROM " . tablename('wxz_shoppingmall_invite_code') . " WHERE {$condition} ORDER BY `id` DESC limit $start , $psize";

$list = pdo_fetchall($sql);
$pager = pagination($total, $pindex, $psize);

include $this->template('web/invite_code_list');
?>

?>
