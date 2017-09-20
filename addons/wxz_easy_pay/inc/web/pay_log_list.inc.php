<?php

/**
 * 
 * 支付失败日志
 */
global $_W, $_GPC;

$pindex = intval($_GPC['page']);
$pindex = max($pindex, 1);
$psize = 15;

$start = ($pindex - 1) * $psize;
$condition = "`uniacid`=:uniacid";
$pars = array();
$pars[':uniacid'] = $_W['uniacid'];

$sql = "SELECT count(*) as num FROM " . tablename('wxz_easy_pay_log') . " WHERE {$condition}";
$total = pdo_fetchcolumn($sql, $pars);

$sql = "SELECT * FROM " . tablename('wxz_easy_pay_log') . " WHERE {$condition} ORDER BY `id` DESC limit $start , $psize";
$list = pdo_fetchall($sql, $pars);
$pager = pagination($total, $pindex, $psize);

include $this->template('web/pay_log_list');
?>
