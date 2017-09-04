<?php

global $_W, $_GPC;


$pindex = intval($_GPC['page']);
$pindex = max($pindex, 1);
$psize = 15;

$start = ($pindex - 1) * $psize;

$pars = array();
$pars[':uniacid'] = $_W['uniacid'];
$condition ='';
if ($_GPC['order_time_end']) {
    $order_time_end = strtotime($_GPC['order_time_end'] . ' 00:00:00');
    $condition .= " AND o.qi_pay_time>='{$order_time_end}'";
    $condition_count .= " AND qi_pay_time>='{$order_time_end}'";
}

if($_GPC['status']){
	$sql = "SELECT count(*) as num FROM " . tablename('hangyi_order'). " as o left join ".tablename('core_paylog')." as p on o.id=p.tid WHERE p.`status`=1 and p.`module`='wxz_easy_pay' and `we_pay_sell_status`='".$_GPC['status']."' ".$condition_count;
	$total = pdo_fetchcolumn($sql, $pars);

	$sql = "SELECT pr.goodsImg as goodsImg,o.buy_nick,o.qi_pay_time as qi_pay_time,o.give_money as give_money,o.id as id,o.goodsPriceTotalReal as goodsPriceTotalReal,o.sell_nickname as sell_nickname,o.out_trade_no as out_trade_no,o.sell_openid as sell_openid,o.goodsName as goodsName,o.we_pay_sell_status as we_pay_sell_status,o.qi_pay_time as qi_pay_time FROM " . tablename('hangyi_order') . " as o left join ".tablename('core_paylog')." as p on o.id=p.tid left join ".tablename('hangyi_product')." as pr on o.pid=pr.id WHERE  p.`status`=1  and p.`module`='wxz_easy_pay' and p.uniacid='".$_W['uniacid']."' and o.`we_pay_sell_status`='".$_GPC['status']."' ".$condition." ORDER BY o.`id` DESC limit $start , $psize";
	$list = pdo_fetchall($sql, $pars);
}else{
	$sql = "SELECT count(*) as num FROM " . tablename('hangyi_order'). " as o left join ".tablename('core_paylog')." as p on o.id=p.tid WHERE p.`status`=1 and p.`module`='wxz_easy_pay' ".$condition_count;
	$total = pdo_fetchcolumn($sql, $pars);

	$sql = "SELECT pr.goodsImg as goodsImg,o.buy_nick,o.qi_pay_time as qi_pay_time,o.give_money as give_money,o.id as id,o.goodsPriceTotalReal as goodsPriceTotalReal,o.sell_nickname as sell_nickname,o.out_trade_no as out_trade_no,o.sell_openid as sell_openid,o.goodsName as goodsName,o.we_pay_sell_status as we_pay_sell_status,o.qi_pay_time as qi_pay_time FROM " . tablename('hangyi_order') . " as o left join ".tablename('core_paylog')." as p on o.id=p.tid left join ".tablename('hangyi_product')." as pr on o.pid=pr.id WHERE p.`status`=1 and p.`module`='wxz_easy_pay' and p.uniacid='".$_W['uniacid']."' ".$condition." ORDER BY o.`id` DESC limit $start , $psize";
	$list = pdo_fetchall($sql, $pars);
}
$my_rate = pdo_get('hangyi_my_rate', array('uniacid' => $_W['uniacid']));
$pager = pagination($total, $pindex, $psize);
if($list){
	foreach($list as $key=>$dval){
		if($my_rate['my_rate']){
			if($my_rate['my_rate']>0){
				$list[$key]['gmoney'] = $dval['goodsPriceTotalReal']+($dval['goodsPriceTotalReal']*$my_rate['my_rate']/10000);
			//	$list[$key]['gmoney'] = sprintf("%.2f", $list[$key]['gmoney']);
				$list[$key]['gmoney'] =  $list[$key]['gmoney'];
			
			}else if($my_rate['my_rate']<0){
				$kouquan  = -1*$dval['goodsPriceTotalReal']*$my_rate['my_rate']/10000;
				if($kouquan<0.01){
					$kouquan = 0.01;
				}
				
				$list[$key]['gmoney'] = $dval['goodsPriceTotalReal']-$kouquan;
				
			
				
			}else{
				$list[$key]['gmoney'] =  $dval['goodsPriceTotalReal'];
			
			}
			
		}else{
		
			$list[$key]['gmoney'] = $dval['goodsPriceTotalReal'];
			
			
		}
		
		$list[$key]['gmoney'] =$list[$key]['gmoney']*100;
		$df = explode(".",$list[$key]['gmoney']);
		if(count($df)==2){
			$list[$key]['gmoney'] = $df[0];
		}
		$list[$key]['gmoney'] = $list[$key]['gmoney']/100;
		if($dval['goodsPriceTotalReal']==1){
			$list[$key]['gmoney'] = 1;
		}
	}
}

include $this->template('pay_sell');