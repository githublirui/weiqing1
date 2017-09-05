<?php

if (!pdo_fieldexists('hangyi_peizhi', 'uniacid')) {
    pdo_query("ALTER TABLE " . tablename('hangyi_peizhi') . " ADD `uniacid` int(10) DEFAULT 0;");
}
if (!pdo_fieldexists('hangyi_add_font', 'uniacid')) {
    pdo_query("ALTER TABLE " . tablename('hangyi_add_font') . " ADD `uniacid` int(10) DEFAULT 0;");
}
if (!pdo_fieldexists('hangyi_tplset', 'uniacid')) {
    pdo_query("ALTER TABLE " . tablename('hangyi_tplset') . " ADD `uniacid` int(10) DEFAULT 0;");
}
if (!pdo_fieldexists('hangyi_my_rate', 'uniacid')) {
    pdo_query("ALTER TABLE " . tablename('hangyi_my_rate') . " ADD `uniacid` int(10) DEFAULT 0;");
}

if (!pdo_tableexists('hangyi_tplset')) {
    pdo_query("CREATE TABLE " . tablename('hangyi_tplset') . " (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `title_color` varchar(50) DEFAULT NULL,
  `tpl_word_1` varchar(255) DEFAULT NULL,
  `tpl_word_1_color` varchar(20) DEFAULT NULL,
  `tpl_word_2` varchar(255) DEFAULT NULL,
  `tpl_word_2_color` varchar(20) DEFAULT NULL,
  `tpl_word_3` varchar(255) DEFAULT NULL,
  `tpl_word_3_color` varchar(20) DEFAULT NULL,
  `tpl_word_4` varchar(255) DEFAULT NULL,
  `tpl_word_4_color` varchar(20) DEFAULT NULL,
  `uniacid` int(10) DEFAULT NULL,
  `tpl_type` int(10) DEFAULT '0' COMMENT '1,订单支付成功通知，2商品发货通知',
  `template_id` varchar(100) DEFAULT NULL,
  `edit_time` int(10) DEFAULT NULL,
  `status` int(1) DEFAULT '0' COMMENT '0停用通知，1启用通知',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;");
}

if (!pdo_tableexists('wxz_easy_pay_page')) {
    pdo_query("CREATE TABLE " . tablename('wxz_easy_pay_page') . " (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `uniacid` INT(11) NOT NULL,
  `type` VARCHAR(50) NOT NULL COMMENT '页面类型',
  `title` VARCHAR(500) NOT NULL COMMENT '标题',
  `desc` TEXT NOT NULL COMMENT '描述',
  `img` VARCHAR(255) NOT NULL COMMENT '图片',
  `link` VARCHAR(255) NOT NULL COMMENT '链接地址',
  `update_at` INT(11) NOT NULL,
  `create_at` INT(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8;");
}