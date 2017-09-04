<?php


	include_once('./WxHongBaoHelper.php');

	$commonUtil = new CommonUtil();
	$wxHongBaoHelper = new WxHongBaoHelper();
	$openid = "oeXy1wGgpd5Y3W9OxQk389MR5uQA";
	$wxHongBaoHelper->setParameter("mch_appid", "wx1581c49d62e41820");
	$wxHongBaoHelper->setParameter("mchid", "6d88ab69399168ba51b7c7fdd398931a");//商户号
	$wxHongBaoHelper->setParameter("nonce_str", $commonUtil->create_noncestr());//随机字符串，丌长于 32 位
	$wxHongBaoHelper->setParameter("partner_trade_no", WxPayConfig::MCHID.date('YmdHis').rand(1000, 9999));//订单号
	$wxHongBaoHelper->setParameter("openid", $openid);//用户openid
	$wxHongBaoHelper->setParameter("check_name", "NO_CHECK");
	$wxHongBaoHelper->setParameter("re_user_name", "");
	$wxHongBaoHelper->setParameter("amount", 100);//付款金额
	$wxHongBaoHelper->setParameter("desc", "商品付款到账");
	$wxHongBaoHelper->setParameter("spbill_create_ip", "139.224.2.158");
	
	
	$postXml = $wxHongBaoHelper->create_hongbao_xml();
	//logger2($postXml);
	$url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers";
	
	$responseXml = $wxHongBaoHelper->curl_post_ssl($url, $postXml);
	//logger2($responseXml);

	$responseObj = simplexml_load_string($responseXml, 'SimpleXMLElement', LIBXML_NOCDATA);
	print_R($responseObj);die;
	print_R($responseObj);
	if ($responseObj->return_code == "SUCCESS"){
		
	}

