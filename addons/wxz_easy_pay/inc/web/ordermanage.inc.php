<?php
global $_W, $_GPC;
if (isset($_GPC['status']) && $_GPC['status'] !=2) {
    $condition .= " AND p.status={$_GPC['status']}";
}

if ($_GPC['words']) {
    $condition .= " AND o.goodsName like'%{$_GPC['words']}%' ";
}

//开始时间，结束时间
if ($_GPC['order_time_start']) {
    $order_time_start = strtotime($_GPC['order_time_start'] . ' 00:00:00');
    $condition .= " AND o.add_time>='{$order_time_start}'";
}
//结束时间
if ($_GPC['order_time_end']) {
    $order_time_end = strtotime($_GPC['order_time_end'] . ' 00:00:00');
    $condition .= " AND o.add_time>='{$order_time_end}'";
}

$pindex = intval($_GPC['page']);
$pindex = max($pindex, 1);
$psize = 15;

$start = ($pindex - 1) * $psize;

$pars = array();
$pars[':uniacid'] = $_W['uniacid'];

$sql = "SELECT count(*) as num FROM " . tablename('hangyi_order') . " as o left join ".tablename('core_paylog')." as p on o.id=p.tid WHERE p.`module`='wxz_easy_pay' and p.uniacid=:uniacid  and o.`out_trade_no` IS NOT NULL  {$condition} ";
$total = pdo_fetchcolumn($sql, $pars);

$sql = "SELECT p.tid as tid,pr.goodsName as goodsName,p.status as status,o.buy_nick as buy_nick,o.sell_nickname,o.goodsPriceTotal as goodsPriceTotal,o.goodsPriceTotalReal as goodsPriceTotalReal,o.add_time as add_time,o.pay_time as pay_time,pr.goodsImg as goodsImg FROM " . tablename('hangyi_order') . " as o left join ".tablename('hangyi_product')." as pr on pr.id=o.pid left join ".tablename('core_paylog')." as p on o.id=p.tid WHERE  p.`module`='wxz_easy_pay' and p.uniacid=:uniacid and o.`out_trade_no` IS NOT NULL {$condition} ORDER BY o.`id` DESC  limit $start , $psize";
$list = pdo_fetchall($sql, $pars);
$pager = pagination($total, $pindex, $psize);




include $this->template('orders');
exit();
