<?php

global $_W, $_GPC;

$condition = "o.uniacid={$_W['uniacid']}";

if ($_GPC['pay_status']) {
    $condition .= " AND o.pay_status={$_GPC['pay_status']}";
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
    $order_time_end = strtotime($_GPC['order_time_end'] . ' 23:59:59');
    $condition .= " AND o.add_time<='{$order_time_end}'";
}

if ($_GPC['order_id']) {
    $condition = " o.id='{$_GPC['order_id']}'";
}

$pindex = intval($_GPC['page']);
$pindex = max($pindex, 1);
$psize = 15;

$start = ($pindex - 1) * $psize;

$pars = array();
$pars[':uniacid'] = $_W['uniacid'];

$tableOrderName = tablename('hangyi_order');

$sql = "SELECT count(*) as num FROM {$tableOrderName} o where {$condition}";
$total = pdo_fetchcolumn($sql);

$sql = "SELECT * FROM {$tableOrderName} o where {$condition} ORDER BY o.`id` DESC  limit $start , $psize";

$list = pdo_fetchall($sql);
$pager = pagination($total, $pindex, $psize);

$proInfos = array();
$userInfos = array();
foreach ($list as $k => $row) {
    if (!isset($proInfos[$row['pid']])) {
        $proInfos[$row['pid']] = pdo_get('hangyi_product', array('id' => $row['pid']));
    }
    $list[$k]['proinfo'] = $proInfos[$row['pid']];

    $productIds = explode(',', $row['pids']);

    $goodnums = explode(',', $row['goodsNums']);
    $attrs = explode(',', $row['attrs']);
  
    $list[$k]['buyproinfos'] = $row['goodsName'] . "\n";

    foreach ($productIds as $pk => $productId) {
        if (!isset($proInfos[$productId])) {
            $proInfos[$productId] = pdo_get('hangyi_product', array('id' => $productId));
        }
        $list[$k]['buyproinfos'] .= $proInfos[$productId]['goodsNameExt'] . $attrs[$pk] . $goodnums[$k] . "个\n";
    }

    //卖家信息
    if ($row['sell_id']) {
        if (!isset($userInfos[$row['sell_id']])) {
            $proInfos[$row['sell_id']] = pdo_get('hangyi_user', array('uid' => $row['sell_id']));
        }
        $list[$k]['sellInfo'] = $proInfos[$row['sell_id']];
    }

    //买家信息
    if ($row['buy_id']) {
        if (!isset($userInfos[$row['buy_id']])) {
            $proInfos[$row['buy_id']] = pdo_get('hangyi_user', array('uid' => $row['buy_id']));
        }
        $list[$k]['buyInfo'] = $proInfos[$row['buy_id']];
    }
}

include $this->template('ordermanage');
exit();
