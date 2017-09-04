<?php
//远程提供sql 语句 修复数据库
$sql_arr[] = array("method"=>"create_table","insert_tab"=>"hangyi_peizhi","sql_str_drop"=>"DROP TABLE IF EXISTS `ims_hangyi_peizhi`;","create_sql"=>"CREATE TABLE `ims_hangyi_peizhi` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `mchid` varchar(100) DEFAULT NULL,
  `mc_key` varchar(100) DEFAULT NULL,
  `edit_time` int(10) DEFAULT NULL,
  `helpurl` varchar(255) DEFAULT NULL,
  `notifyurl` varchar(255) DEFAULT NULL,
  `i_val` int(11) DEFAULT NULL,
  `add_product_url` varchar(255) DEFAULT NULL,
  `orderlist_url` varchar(255) DEFAULT NULL,
  `ordermanager_url` varchar(255) DEFAULT NULL,
  `myorder_url` varchar(255) DEFAULT NULL,
  `apiclient_key` text,
  `apiclient_cert` text,
  `rootca` text,
  `paysell_isauto` tinyint(1) DEFAULT '0' COMMENT '0表示不自动打款，1表示自动打款',
  `uniacid` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;");

$sql_arr[] = array("method"=>"create_table","insert_tab"=>"hangyi_add_font","sql_str_drop"=>"DROP TABLE IF EXISTS `ims_hangyi_add_font`;","create_sql"=>"CREATE TABLE `ims_hangyi_add_font` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `add_one` varchar(255) DEFAULT NULL,
  `add_two` varchar(255) DEFAULT NULL,
  `add_three` varchar(255) DEFAULT NULL,
  `add_four` varchar(255) DEFAULT NULL,
  `uniacid` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;");

$sql_arr[] = array("method"=>"create_table","insert_tab"=>"hangyi_hbset","sql_str_drop"=>"DROP TABLE IF EXISTS `ims_hangyi_hbset`;","create_sql"=>"CREATE TABLE `ims_hangyi_hbset` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `img_text` varchar(255) DEFAULT NULL,
  `uniacid` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;");


$sql_arr[] = array("method"=>"create_table","insert_tab"=>"hangyi_user","sql_str_drop"=>"DROP TABLE IF EXISTS `ims_hangyi_user`;","create_sql"=>"CREATE TABLE `ims_hangyi_user` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nickname` varchar(255) DEFAULT NULL,
  `tel` varchar(255) DEFAULT NULL,
  `headimgurl` varchar(255) DEFAULT NULL,
  `add_time` int(10) DEFAULT NULL,
  `uid` int(10) DEFAULT NULL,
  `openid` varchar(255) DEFAULT NULL,
  `weixin` varchar(30) DEFAULT NULL,
  `mysaddress` varchar(255) DEFAULT NULL,
  `order_address` varchar(50) DEFAULT NULL,
  `realname` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;");

$sql_arr[] = array("method"=>"create_table","insert_tab"=>"hangyi_my_rate","sql_str_drop"=>"DROP TABLE IF EXISTS `ims_hangyi_my_rate`;","create_sql"=>"CREATE TABLE `ims_hangyi_my_rate` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `my_rate` varchar(30) DEFAULT NULL COMMENT '费率 正数给奖励，负数扣钱',
  `uniacid` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;");
/*
$sql_arr[] = array("method"=>"create_table","insert_tab"=>"hangyi_order","sql_str_drop"=>"DROP TABLE IF EXISTS `ims_hangyi_order`;","create_sql"=>"CREATE TABLE `ims_hangyi_order` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `price` varchar(10) DEFAULT NULL,
  `buy_id` int(10) DEFAULT NULL,
  `pay_status` int(1) DEFAULT '0' COMMENT '0未付款2已付款',
  `order_status` int(1) DEFAULT '0' COMMENT '0未发货，1已发货',
  `pid` int(11) DEFAULT NULL COMMENT '产品id',
  `pay_time` int(10) DEFAULT NULL,
  `add_time` int(10) DEFAULT NULL,
  `fh_time` int(10) DEFAULT NULL,
  `sell_id` int(11) DEFAULT NULL,
  `goodsName` varchar(255) DEFAULT NULL,
  `goodsNum` int(11) DEFAULT '1' COMMENT '产品数',
  `goodsPriceTotal` varchar(255) DEFAULT NULL,
  `buy_nick` varchar(50) DEFAULT NULL COMMENT '收货人',
  `sell_openid` varchar(255) DEFAULT NULL,
  `buy_openid` varchar(255) DEFAULT NULL,
  `postscript` varchar(255) DEFAULT NULL COMMENT '买家留言',
  `cell` varchar(255) DEFAULT NULL,
  `weixin` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `goodsPriceTotalReal` varchar(255) DEFAULT NULL,
  `out_trade_no` varchar(255) DEFAULT NULL,
  `send_time` int(10) DEFAULT '0' COMMENT '发货时间',
  `get_time` int(10) DEFAULT '0' COMMENT '收货时间',
  `pay_price` float(10,2) DEFAULT NULL,
  `we_pay_sell_status` int(1) DEFAULT '1' COMMENT '1未给出售者付款，2已付款给销售者',
  `qi_pay_time` int(10) DEFAULT NULL,
  `tracking_number` varchar(100) DEFAULT NULL COMMENT '快递单号',
  `give_money` varchar(20) DEFAULT NULL COMMENT '负数为扣钱正数为加钱',
  `buy_nickname` varchar(50) DEFAULT NULL COMMENT '买家昵称',
  `sell_nickname` varchar(50) DEFAULT NULL COMMENT '卖家昵称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;");
*/

if($sql_arr){
	echo json_encode($sql_arr);
}
?>




