<?php

if (!pdo_fieldexists('wxz_shoppingmall_order', 'success_at')) {
    pdo_query("ALTER TABLE " . tablename('wxz_shoppingmall_order') . " ADD `success_at` int(11) DEFAULT '0';");
}

if (!pdo_fieldexists('wxz_shoppingmall_fans', 'account')) {
    pdo_query("ALTER TABLE " . tablename('wxz_shoppingmall_fans') . " ADD `account` int(11) DEFAULT '0';");
}

if (!pdo_tableexists('wxz_shoppingmall_reply_setting')) {
    pdo_query("CREATE TABLE " . tablename('wxz_shoppingmall_reply_setting') . " (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `uniacid` INT(11) NOT NULL,
  `rid` INT(11) NOT NULL,
  `title` VARCHAR(500) NOT NULL COMMENT '标题',
  `desc` TEXT NOT NULL COMMENT '描述',
  `img` VARCHAR(255) NOT NULL COMMENT '图片',
  `link` VARCHAR(255) NOT NULL COMMENT '链接地址',
  `isdel` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除',
  `update_at` INT(11) NOT NULL,
  `create_at` INT(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8;");
}

if (pdo_fieldexists('wxz_shoppingmall_order', 'park_pay_type')) {
    pdo_query("ALTER TABLE " . tablename('wxz_shoppingmall_order') . " CHANGE `park_pay_type` `park_pay_type` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '计费方式';");
}

if (pdo_fieldexists('wxz_shoppingmall_fans', 'level')) {
    pdo_query("ALTER TABLE " . tablename('wxz_shoppingmall_fans') . " CHANGE `level` `level` TINYINT(2) DEFAULT 0 NOT NULL COMMENT '会员等级';");
}

if (!pdo_fieldexists('wxz_shoppingmall_fans', 'address')) {
    pdo_query("ALTER TABLE " . tablename('wxz_shoppingmall_fans') . " ADD `address` varchar(255) DEFAULT '';");
}