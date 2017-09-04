<?php

global $_W, $_GPC;


$pindex = intval($_GPC['page']);
$pindex = max($pindex, 1);
$psize = 15;

$start = ($pindex - 1) * $psize;

$pars = array();
$pars[':uniacid'] = $_W['uniacid'];

$sql = "SELECT count(*) as num FROM " . tablename('hangyi_user');
$total = pdo_fetchcolumn($sql, $pars);

$sql = "SELECT * FROM " . tablename('hangyi_user') . " ORDER BY `uid` DESC limit $start , $psize";
$list = pdo_fetchall($sql, $pars);
$pager = pagination($total, $pindex, $psize);


include $this->template('user');