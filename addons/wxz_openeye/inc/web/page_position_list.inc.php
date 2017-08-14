<?php

/**
 * 订单列表
 */
global $_W, $_GPC;
require_once WXZ_OPENEYE . '/source/Page.class.php';

$pages = Page::$pages;
$pageTypes = Page::$pageTypes;

$pindex = intval($_GPC['page']);
$pindex = max($pindex, 1);
$psize = 15;

$start = ($pindex - 1) * $psize;
$condition = '`uniacid`=:uniacid';

$pars = array();
$pars[':uniacid'] = $_W['uniacid'];

if ($_GPC['vpage']) {
    $condition .= " AND page='{$_GPC['vpage']}'";
}
if ($_GPC['page_type']) {
    $condition .= " AND page_type='{$_GPC['page_type']}'";
}

$sql = "SELECT count(*) as num FROM " . tablename('wxz_openeye_page_position') . " WHERE {$condition}";
$total = pdo_fetchcolumn($sql, $pars);

$sql = "SELECT * FROM " . tablename('wxz_openeye_page_position') . " WHERE {$condition} ORDER BY `order` DESC limit $start , $psize";
$list = pdo_fetchall($sql, $pars);

foreach ($list as $k => $row) {
    $page_info_sql = "SELECT * FROM " . tablename('wxz_openeye_page') . " WHERE id={$row['page_id']}";
    $row['page_info'] = pdo_fetch($page_info_sql);
    $list[$k] = $row;
}
$pager = pagination($total, $pindex, $psize);

include $this->template('web/page_position_list');
?>
