<?php
/**
 * 简单支付模块微站定义
 *
 * @author hangyi
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Wxz_easy_payModuleSite extends WeModuleSite {

	public function doMobileGnfm() {
		global $_GPC, $_W;
		load()->model('mc');
		$fan = mc_fansinfo($_W['openid']);
		if (!empty($fan) && !empty($fan['openid'])) {
			$userinfo = $fan;
		}else{
			$userinfo = mc_oauth_userinfo();
		}
		include $this->template('gnfm');
		//这个操作被定义用来呈现 功能封面
	}
	public function doWebHflist() {
		//这个操作被定义用来呈现 规则列表
	}
	

	public function doMobileSydh() {
		//这个操作被定义用来呈现 微站首页导航图标
	}
	public function doMobileWzpersion() {
		//这个操作被定义用来呈现 微站个人中心导航
	}
	public function doMobileWzkjgn() {
		//这个操作被定义用来呈现 微站快捷功能导航
	}
	
	public function payResult($params){
		global $_GPC, $_W;
		$uniacid = $_W['uniacid'];
		 if ($params['result'] == 'success' && $params['from'] == 'notify') {
				$pid = $params['tid'];
				$account = $_W['account'];
				$orders = pdo_get('hangyi_order', array('id' =>$pid));
				$orders_pay_log = pdo_get('core_paylog', array('tid' =>$pid,'uniacid'=>$uniacid));
				if($orders_pay_log['status']==1){
					$result = pdo_query("UPDATE ".tablename('hangyi_order')." SET `pay_time`='".time()."',`pay_status`=2  WHERE id = '".$pid."' limit 1");
				}else{
					exit();
				}
				//$result = pdo_query("UPDATE ".tablename('hangyi_order')." SET `pay_time`='".time()."'  WHERE id = '".$pid."' limit 1");
				$result = pdo_query("UPDATE ".tablename('hangyi_product')." SET `sell_num`=`sell_num`+1,`goodsStock`=`goodsStock`-1  WHERE id = '".$pid."' limit 1");
				
				load()->classs('weixin.account');
				$acc = new WeiXinAccount($account);
				
				$setting_fh = pdo_get('hangyi_tplset', array('tpl_type' => 1,'uniacid'=>$uniacid));
				if($setting_fh['status']){
					$data = array(
							'first' => array(
								'value' => "单号".$pid."，买家".$orders['buy_nickname']."支付成功",
								'color' => $setting_fh['title_color']
							),
							'orderMoneySum' => array(
								'value' => $orders['goodsPriceTotalReal']."元",
								'color' => $setting_fh['tpl_word_1_color']
							),
							'orderProductName' => array(
								'value' =>  $orders['goodsName'],
								'color' => $setting_fh['tpl_word_2_color']
							),
							'Remark' => array(
								'value' => "您此比订单款需要48小时内到账",
								'color' => $setting_fh['tpl_word_4_color']
							),
						);
						$acc->sendTplNotice($orders['sell_openid'], $setting_fh['template_id'], $data, '', $topcolor = '#FF683F') ;
				}else{
					$data = array(
							'first' => array(
								'value' => "单号".$pid."，买家".$orders['buy_nickname']."支付成功",
								'color' => '#ff510'
							),
							'orderMoneySum' => array(
								'value' => $orders['goodsPriceTotalReal']."元",
								'color' => '#ff510'
							),
							'orderProductName' => array(
								'value' =>  $orders['goodsName'],
								'color' => '#ff510'
							),
							'Remark' => array(
								'value' => "您此比订单款需要48小时内到账",
								'color' => '#ff510'
							),
						);
				}
				
				
				
				//企业付款
			
			$setting = pdo_get('hangyi_peizhi', array('id' => 1));
			if(empty($setting['paysell_isauto'])){
				exit();
			}
			if($setting['paysell_isauto'] !=1){
				exit();
			}
			$orders = pdo_get('hangyi_order', array('id' =>$pid));
			$uniacid = $_W['uniacid'];
			//file_put_contents(IA_ROOT."/1.txt","uniacid=>".$uniacid);
			$orderinfo = $orders;
			$orderinfo_pay = pdo_get('core_paylog', array('tid' => $pid,"status"=>1,"uniacid"=>$uniacid,"module"=>"wxz_easy_pay"));
			//file_put_contents(IA_ROOT."/2.txt","orderinfo_pay=>".json_encode($orderinfo_pay));die;
			$my_rate = pdo_get('hangyi_my_rate', array('id' => 1));
			
			if($orderinfo_pay['status'] !=1){
			
				exit();
			}
			if($orderinfo['we_pay_sell_status']==2){
				
				exit();
			}

			//if(!$orderinfo || empty($orderinfo['sell_openid']) || $orderinfo['goodsPriceTotalReal']<1){
			if(!$orderinfo || empty($orderinfo['sell_openid'])){
				exit();
			}

			$peizhi = pdo_get('hangyi_peizhi', array('id' =>1));
			$gzhinfo = pdo_get('account_wechats', array('uniacid' =>$uniacid));
			//$i = $uniacid;
			//$gzhinfo = $_W['cache']['uniaccount:'.$i];

			define("ZAPPID",$gzhinfo['key']);
			define("MCHID_D",$peizhi['mchid']);
			define("MC_KEY_D",$peizhi['mc_key']);
			define("MSECRET",$gzhinfo['secret']);
//file_put_contents(IA_ROOT."/2.txt","ddd=>".IA_ROOT."/addons/wxz_easy_pay/pay/lib/WxPay.Api.php");die;
			$productinfo = pdo_get('hangyi_product', array('id' => $orderinfo['pid']));
			
			require_once IA_ROOT."/addons/wxz_easy_pay/pay/lib/WxPay.Api.php";
			//require_once IA_ROOT."/addons/wxz_easy_pay/pay/example/WxPay.JsApiPay.php";
			include_once(IA_ROOT.'/addons/wxz_easy_pay/pay/mch_pay/WxHongBaoHelper.php');
			define('APPID', WxPayConfig::APPID); 
			//file_put_contents(IA_ROOT."/2.txt","ddd=>".APPID);die;
			//商户id
			define('MCHID', WxPayConfig::MCHID);
			
			//通加密串,微信支付密钥，商户平台中，账户设置->安全设置->API安全->API密钥：api设置
			define('PARTNERKEY',WxPayConfig::KEY);
		//	file_put_contents(IA_ROOT."/2.txt","ddd=>".MCHID."key=>".PARTNERKEY);die;
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
					
					$result = pdo_query("UPDATE ".tablename('hangyi_order')." SET `give_money`='".($send_money)."',`we_pay_sell_status`='2',`qi_pay_time`='".time()."'  WHERE id = '".$pid."' and `we_pay_sell_status`=1 limit 1");
				//	echo "ok";
				}else{
				//	echo ($responseObj->err_code_des);
				}




			
			
			
			
			
				
			 
			 exit();
        //此处会处理一些支付成功的业务代码
		}
		//因为支付完成通知有两种方式 notify，return,notify为后台通知,return为前台通知，需要给用户展示提示信息
		//return做为通知是不稳定的，用户很可能直接关闭页面，所以状态变更以notify为准
		//如果消息是用户直接返回（非通知），则提示一个付款成功
		//如果是JS版的支付此处的跳转则没有意义
		if ($params['from'] == 'return') {
			if ($params['result'] == 'success') {
				$pid = $params['tid'];
				
				
				
				message('支付成功！', $this->createMobileUrl('buyorders'), 'success');
			} else {
				message('支付失败！', $this->createMobileUrl('buyorders'), 'error');
			}
		}
		
		
	}

	public function doMobileBuycentral() {
		global $_GPC, $_W;

		include $this->template('buy');
		
		//这个操作被定义用来呈现买家功能
	}

}