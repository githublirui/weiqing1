<?php

/**
 * 专题视频列表
 */
global $_W, $_GPC;
require_once WXZ_OPENEYE . '/source/Page.class.php';

$pindex = intval($_GPC['page']);
$pindex = max($pindex, 1);
$psize = 15;

$sid = $_GPC['sid'];

$start = ($pindex - 1) * $psize;
$condition = "`uniacid`=:uniacid AND special_id={$sid}";

$pars = array();
$pars[':uniacid'] = $_W['uniacid'];

$sql = "SELECT count(*) as num FROM " . tablename('wxz_openeye_special_page') . " WHERE {$condition}";
$total = pdo_fetchcolumn($sql, $pars);

$sql = "SELECT * FROM " . tablename('wxz_openeye_special_page') . " WHERE {$condition} ORDER BY `id` DESC limit $start , $psize";
$list = pdo_fetchall($sql, $pars);
$pager = pagination($total, $pindex, $psize);

foreach ($list as $k => $row) {
    $page_info_sql = "SELECT * FROM " . tablename('wxz_openeye_page') . " WHERE id={$row['page_id']}";
    $special_info_sql = "SELECT * FROM " . tablename('wxz_openeye_special') . " WHERE id={$row['special_id']}";
    $row['page'] = pdo_fetch($page_info_sql);
    $row['special'] = pdo_fetch($special_info_sql);
    $list[$k] = $row;
}

include $this->template('web/special_page_list');
?>
