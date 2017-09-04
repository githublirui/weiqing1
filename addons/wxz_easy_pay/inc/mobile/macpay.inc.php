<?php
//print_R($_FILES);
global $_GPC, $_W;
$orderid = (int)$_GPC['orderid'];
$uniacid = $_W['uniacid'];

$orderinfo = pdo_get('hangyi_order', array('uniacid' => $uniacid));
$orderinfo_pay = pdo_get('core_paylog', array('tid' => $orderid,"status"=>1,"module"=>"wxz_easy_pay"));
$my_rate = pdo_get('hangyi_my_rate', array('uniacid' => $uniacid));
if(empty($orderinfo)){
	echo "没有找到该订单";
	exit();
}
if($orderinfo_pay['status'] !=1){
	echo "该订单还客户没有支付";
	exit();
}
if($orderinfo['we_pay_sell_status']==2){
	echo "已经付款";
	exit();
}
//if(!$orderinfo || empty($orderinfo['sell_openid']) || $orderinfo['goodsPriceTotalReal']<1){
if(!$orderinfo || empty($orderinfo['sell_openid'])){
	exit("没有查到该id未付款信息");
}

$peizhi = pdo_get('hangyi_peizhi', array('uniacid' =>$uniacid));
$i = $_GPC['i'];
$gzhinfo = $_W['cache']['uniaccount:'.$i];

define("ZAPPID",$gzhinfo['key']);
define("MCHID_D",$peizhi['mchid']);
define("MC_KEY_D",$peizhi['mc_key']);
define("MSECRET",$gzhinfo['secret']);
define("CURUNIACID",$_W['uniacid']);

$productinfo = pdo_get('hangyi_product', array('id' => $orderinfo['pid']));
require_once "../addons/wxz_easy_pay/pay/lib/WxPay.Api.php";
//require_once "../addons/wxz_easy_pay/pay/example/WxPay.JsApiPay.php";
include_once('../addons/wxz_easy_pay/pay/mch_pay/WxHongBaoHelper.php');
define('APPID', WxPayConfig::APPID); 
//商户id
define('MCHID', WxPayConfig::MCHID);
//通加密串,微信支付密钥，商户平台中，账户设置->安全设置->API安全->API密钥：api设置
define('PARTNERKEY',WxPayConfig::KEY);
if($productinfo['goodsName']){
	$productinfo['goodsName'] ="商品ID".$orderinfo['pid']."付款";
}
	$commonUtil = new CommonUtil();
	$wxHongBaoHelper = new WxHongBaoHelper();
	$openid = $orderinfo['sell_openid'];
	$wxHongBaoHelper->setParameter("mch_appid", WxPayConfig::APPID);
	$wxHongBaoHelper->setParameter("mchid", WxPayConfig::MCHID);//商户号
	$wxHongBaoHelper->setParameter("nonce_str", $commonUtil->create_noncestr());//随机字符串，丌长于 32 位
	$wxHongBaoHelper->setParameter("partner_trade_no", WxPayConfig::MCHID.date('YmdHis').rand(1000, 9999));//订单号
	$wxHongBaoHelper->setParameter("openid", $openid);//用户openid
	$wxHongBaoHelper->setParameter("check_name", "NO_CHECK");
	$wxHongBaoHelper->setParameter("re_user_name", "");
	/*if($my_rate['my_cate'] && $my_rate['my_cate']>0){
		$gmoney = ($orderinfo['goodsPriceTotal']*100*$my_rate['my_cate'])/10000;
		$send_money =  $orderinfo['goodsPriceTotal']*100+$gmoney;
	}else if($my_rate['my_cate'] && $my_rate['my_cate']<0 && $orderinfo['goodsPriceTotal']>1){
		$gmoney = -($orderinfo['goodsPriceTotal']*100*$my_rate['my_cate'])/10000;
		$send_money =  $orderinfo['goodsPriceTotal']*100+$gmoney;
	}else{
		$gmoney=0;
		$send_money =  $orderinfo['goodsPriceTotal']*100;
	}*/
	
	
	
	
		if($my_rate['my_rate']){
			if($my_rate['my_rate']>0){
				$send_money = $orderinfo['goodsPriceTotalReal']+($orderinfo['goodsPriceTotalReal']*$my_rate['my_rate']/10000);
				
			}else if($my_rate['my_rate']<0){
				$kouquan  = -1*($orderinfo['goodsPriceTotalReal']*$my_rate['my_rate']/10000);
				/*if($kouquan<0.1){
					$kouquan = 0.1;
				}*/
				//echo $my_rate['my_rate'];
				$send_money = $orderinfo['goodsPriceTotalReal']-$kouquan;
				
			}else{
				$send_money =  $orderinfo['goodsPriceTotalReal'];
			}
			
		}else{
			$send_money =  $orderinfo['goodsPriceTotalReal'];
		}
		
		
		$send_money =$send_money*100;
		$df = explode(".",$send_money);
		if(count($df)==2){
			$send_money = $df[0];
		}
		$send_money = $send_money/100;
		if($orderinfo['goodsPriceTotalReal']==1){
			$send_money = 1;
		}
	//echo $send_money;die;
	
	//print_R($send_money);die;
	$wxHongBaoHelper->setParameter("amount", $send_money*100);//付款金额
	$wxHongBaoHelper->setParameter("desc", $productinfo['goodsName']);
	$wxHongBaoHelper->setParameter("spbill_create_ip", "139.224.2.158");
	
	 
	$postXml = $wxHongBaoHelper->create_hongbao_xml();
	
	$url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers";
	
	$responseXml = $wxHongBaoHelper->curl_post_ssl($url, $postXml);
	

	$responseObj = simplexml_load_string($responseXml, 'SimpleXMLElement', LIBXML_NOCDATA);
	
	if ($responseObj->return_code == "SUCCESS" && $responseObj->result_code == "SUCCESS"){
		
		$result = pdo_query("UPDATE ".tablename('hangyi_order')." SET `give_money`='".($send_money)."',`we_pay_sell_status`='2',`qi_pay_time`='".time()."'  WHERE id = '".$orderid."' and `we_pay_sell_status`=1 limit 1");
		echo "ok";
	}else{
		echo ($responseObj->err_code_des);
	}

