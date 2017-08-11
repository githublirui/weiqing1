<?php

global $_W, $_GPC;

$filters = array();
$filters['uniacid'] = $_W['uniacid'];
$filters['nickname'] = $_GPC['nickname'];

$pindex = intval($_GPC['page']);
$pindex = max($pindex, 1);
$psize = 15;

$start = ($pindex - 1) * $psize;
$condition = '`uniacid`=:uniacid';
$pars = array();
$pars[':uniacid'] = $_W['uniacid'];

$sql = "SELECT count(*) as num FROM " . tablename('wxz_openeye_comment') . " WHERE {$condition}";
$total = pdo_fetchcolumn($sql, $pars);

$sql = "SELECT * FROM " . tablename('wxz_openeye_comment') . " WHERE {$condition} ORDER BY `id` DESC limit $start , $psize";
$list = pdo_fetchall($sql, $pars);

foreach ($list as $k => $row) {
    $sql = "SELECT * FROM " . tablename('wxz_openeye_page') . " WHERE id={$row['page_id']}";
    $pageInfo = pdo_fetch($sql);
    $list[$k]['page_info'] = $pageInfo;
}
$pager = pagination($total, $pindex, $psize);

include $this->template('web/comment_list');
?>
