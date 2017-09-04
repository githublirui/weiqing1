<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */

define('IN_MOBILE', true);
require '../../../framework/bootstrap.inc.php';
load()->func('communication'); 













				  
/*file_get_contents("http://hefei.hfwxz.com/app/index.php?i=22&c=entry&do=macpay&m=wxz_easy_pay&orderid=3");
exit();
 $orderinfo = pdo_get('hangyi_order', array('out_trade_no' =>"128877350120170724045224155","pay_status"=>2));
 print_R($orderinfo);die;
exit();*/
$input = file_get_contents('php://input');

$obj = isimplexml_load_string($input, 'SimpleXMLElement', LIBXML_NOCDATA);
$obj = json_encode($obj);
$data = json_decode($obj, true);
$get = $data;

		ksort($get);
		$string1 = '';
		foreach($get as $k => $v) {
			if($v != '' && $k != 'sign') {
				$string1 .= "{$k}={$v}&";
			}
		}
		$peizhi = pdo_get('hangyi_peizhi', array('id' =>1));
		$string1 = $string1."key=".$peizhi['mc_key'];
		$sign = strtoupper(md5($string1));//$_GET['out_trade_no']

	if ($data['result_code'] != 'SUCCESS' || $data['return_code'] != 'SUCCESS') {
		$result = array(
			'return_code' => 'FAIL',
			'return_msg' => empty($data['return_msg']) ? $data['err_code_des'] : $data['return_msg']
		);
		echo array2xml($result);
		exit;
	}
		
		
if($sign==$get['sign']){
	$orderinfo = pdo_get('hangyi_order', array('out_trade_no' => $get['out_trade_no']));
	if($orderinfo['pay_status'] ==1){
		pdo_query("UPDATE ".tablename('hangyi_order')." SET `pay_status`='2',`pay_time`='".time()."' where  `out_trade_no`='".$get['out_trade_no']."'");
		//$orderinfo = pdo_get('hangyi_order', array('out_trade_no' => $get['out_trade_no'],"pay_status"=>2));
		//更新产品库存 更新产品销售个数
		if($orderinfo){
			pdo_query("UPDATE ".tablename('hangyi_product')." SET `goodsStock`=goodsStock-1,`sell_num`=`sell_num`+1 where  `id`='".$orderinfo['pid']."'");
			
			
			
			
			
			
			
			$my_rate = pdo_get('hangyi_my_rate', array('id' => 1));
			if(empty($orderinfo)){
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

			$peizhi = pdo_get('hangyi_peizhi', array('id' =>1));
			$i = $_GPC['i'];
			$gzhinfo = $_W['cache']['uniaccount:'.$i];
			print_R($gzhinfo);die;
			define("ZAPPID",$gzhinfo['key']);
			define("MCHID_D",$peizhi['mchid']);
			define("MC_KEY_D",$peizhi['mc_key']);
			define("MSECRET",$gzhinfo['secret']);

			$productinfo = pdo_get('hangyi_product', array('id' => $orderinfo['pid']));
			//require_once "../addons/wxz_easy_pay/pay/lib/WxPay.Api.php";
			//require_once "../addons/wxz_easy_pay/pay/example/WxPay.JsApiPay.php";
			include_once('../wxz_easy_pay/pay/mch_pay/WxHongBaoHelper.php');
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
				
				
				
				
					if($my_rate['my_rate']){
						if($my_rate['my_rate']>0){
							$send_money = $orderinfo['goodsPriceTotalReal']+($orderinfo['goodsPriceTotalReal']*$my_rate['my_rate']/10000);
							$send_money = sprintf("%.2f", $send_money);
							//$send_money = $list[$key]['gmoney']/100;
						}else if($my_rate['my_rate']<0){
							$kouquan  = ($dval['goodsPriceTotalReal']*$my_rate['my_rate']/10000);
							if($kouquan<0.1){
								$kouquan = 0.1;
							}
							
							$send_money = $orderinfo['goodsPriceTotalReal']-$kouquan;
							
							$send_money = sprintf("%.2f", $send_money);
						//	$send_money = $list[$key]['gmoney']/100;
						}else{
							$send_money = sprintf("%.2f", $orderinfo['goodsPriceTotalReal']);
						}
						
					}else{
						$send_money = sprintf("%.2f", $orderinfo['goodsPriceTotalReal']);
					}
				
				
				//print_R($send_money);die;
				$wxHongBaoHelper->setParameter("amount", $send_money*100);//付款金额
				$wxHongBaoHelper->setParameter("desc", $productinfo['goodsName']);
				$wxHongBaoHelper->setParameter("spbill_create_ip", "139.224.2.158");
				
				
				$postXml = $wxHongBaoHelper->create_hongbao_xml();
				
				$url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers";
				
				$responseXml = $wxHongBaoHelper->curl_post_ssl($url, $postXml);
				

				$responseObj = simplexml_load_string($responseXml, 'SimpleXMLElement', LIBXML_NOCDATA);
				
				if ($responseObj->return_code == "SUCCESS" && $responseObj->result_code == "SUCCESS"){
					
					$result = pdo_query("UPDATE ".tablename('hangyi_order')." SET `give_money`='".($send_money)."',`we_pay_sell_status`='2',`qi_pay_time`='".time()."'  WHERE id = '".$orderid."' and `pay_status`=2 and `we_pay_sell_status`=1 limit 1");
					//echo "ok";
				}else{
				//	echo ($responseObj->err_code_des);
				}
		
		
		
		
		
		
		}
		echo 'success';
	}
	
	//file_put_contents("1.txt",json_encode($orderinfo));
//file_put_contents("2.txt",$get['sign']);
	//$url = "http://hefei.hfwxz.com/app/index.php?i=22&c=entry&do=macpay&m=wxz_easy_pay&orderid=".$orderinfo['id'];
//	$response = ihttp_get($url);
	exit();
	
}


