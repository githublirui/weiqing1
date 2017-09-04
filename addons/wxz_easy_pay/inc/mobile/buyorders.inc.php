<?php
//print_R($_FILES);
global $_GPC, $_W;
load()->model('mc');
		$fan = mc_fansinfo($_W['openid']);
		if (!empty($fan) && !empty($fan['openid'])) {
			$userinfo = $fan;
		}else{
			$userinfo = mc_oauth_userinfo();
		}
$uid = $userinfo['uid'];
$uniacid = $_W['uniacid'];
$sql = "SELECT p.goodsImg as goodsImg,o.id as id,p.goodsName as goodsName,o.pay_time as pay_time,o.order_status as order_status,o.tracking_number as tracking_number FROM `ims_hangyi_order` as o left join `ims_hangyi_product` as p on o.pid=p.id left join `ims_core_paylog` as pl on o.id=pl.tid  where pl.uniacid=:uniacid and pl.status=1 and o.buy_id=:uid and pl.module='wxz_easy_pay' order by o.add_time desc";
$trades_p = pdo_fetchall($sql, array(':uid' => $uid,':uniacid'=>$uniacid));

//print_R($trades_p);die;
include $this->template('user_orders_show');

?>




