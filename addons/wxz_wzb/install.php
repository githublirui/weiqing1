<?php

pdo_query("CREATE TABLE IF NOT EXISTS ".tablename('wxz_wzb_invitation')." (
    `id` int(10) NOT NULL AUTO_INCREMENT,
  `rid` int(10) DEFAULT NULL,
  `uniacid` int(10) DEFAULT NULL,
  `desc1` varchar(255) DEFAULT NULL,
  `desc2` varchar(255) DEFAULT NULL,
  `desc3` varchar(255) DEFAULT NULL,
  `desc4` varchar(255) DEFAULT NULL,
  `bg` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;");


pdo_query("CREATE TABLE IF NOT EXISTS ".tablename('wxz_wzb_zannum')." (
	  `id` int(10) NOT NULL AUTO_INCREMENT,
	  `rid` int(10) unsigned DEFAULT '0',
	  `uniacid` int(10) unsigned DEFAULT '0',
	  `user_id` int(10) unsigned DEFAULT '0',
	  `num` int(10) unsigned DEFAULT '0',
	  PRIMARY KEY (`id`),
	  KEY `rid` (`rid`,`uniacid`) USING BTREE,
	  KEY `uid` (`user_id`) USING BTREE
	) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;");


pdo_query("CREATE TABLE IF NOT EXISTS ".tablename('wxz_wzb_zanpic')." (
	`id` int(10) NOT NULL AUTO_INCREMENT,
	`pic` varchar(255) DEFAULT NULL,
	`dateline` int(10) DEFAULT NULL,
	`uniacid` int(10) unsigned NOT NULL DEFAULT '0',
	`rid` int(10) unsigned NOT NULL DEFAULT '0',
	`isshow` tinyint(1) DEFAULT '0',
	PRIMARY KEY (`id`),
	KEY `uniacid` (`uniacid`) USING BTREE,
	KEY `rid` (`rid`) USING BTREE,
	KEY `isshow` (`isshow`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;");


pdo_query("CREATE TABLE IF NOT EXISTS ".tablename('wxz_wzb_user')." (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) DEFAULT NULL,
  `nickname` varchar(255) DEFAULT NULL,
  `headimgurl` varchar(255) DEFAULT NULL,
  `province` varchar(255) DEFAULT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `sex` varchar(255) DEFAULT NULL,
  `dateline` int(10) DEFAULT NULL,
  `sub_openid` varchar(255) DEFAULT NULL COMMENT '���ĺ�openid',
  `openid` varchar(255) DEFAULT NULL COMMENT '�����openid',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `sub_openid` (`sub_openid`) USING BTREE,
  KEY `openid` (`openid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=142 DEFAULT CHARSET=utf8;");

pdo_query("CREATE TABLE IF NOT EXISTS ".tablename('wxz_wzb_viewer')." (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) DEFAULT NULL,
  `rid` int(10) DEFAULT NULL,
  `dateline` int(10) DEFAULT NULL,
  `share` tinyint(1) DEFAULT '0',
  `amount` int(10) DEFAULT '0',
  `ispay` tinyint(1) DEFAULT '0',
  `rlog` varchar(255) DEFAULT NULL,
  `deposit` int(10) DEFAULT '0',
  `password` text,
  `isshutup` tinyint(1) DEFAULT '0',
  `role` tinyint(1) DEFAULT '0' COMMENT '0��ͨ1����2����Ա',
  `uniacid` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`) USING BTREE,
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;");


pdo_query("CREATE TABLE IF NOT EXISTS ".tablename('wxz_wzb_tx')." (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `fee` varchar(20) NOT NULL DEFAULT '',
  `dateline` int(10) DEFAULT NULL,
  `type` tinyint(1) DEFAULT NULL,
  `rid` int(10) DEFAULT '0',
  `fromid` int(10) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `uniacid` (`uniacid`),
  KEY `rid` (`rid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=213075 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;");

pdo_query("CREATE TABLE IF NOT EXISTS ".tablename('wxz_wzb_spread_adv')." (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) DEFAULT NULL,
  `rid` int(10) DEFAULT NULL,
  `images` varchar(255) DEFAULT NULL,
  `count_time` int(10) DEFAULT NULL,
  `type` tinyint(1) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `dateline` int(10) DEFAULT NULL,
  `bgcolor` varchar(255) DEFAULT NULL,
  `color` varchar(255) DEFAULT NULL,
  `timecolor` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;");


pdo_query("CREATE TABLE IF NOT EXISTS ".tablename('wxz_wzb_share')." (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `share_uid` int(10) DEFAULT NULL,
  `help_uid` int(10) DEFAULT NULL,
  `dateline` int(10) DEFAULT NULL,
  `amount` int(10) DEFAULT NULL,
  `uniacid` int(10) DEFAULT NULL,
  `rid` int(10) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`) USING BTREE,
  KEY `share_uid` (`share_uid`) USING BTREE,
  KEY `help_uid` (`help_uid`) USING BTREE,
  KEY `uniacid` (`uniacid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8;");

pdo_query("CREATE TABLE IF NOT EXISTS ".tablename('wxz_wzb_setting')." (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `share_img` varchar(255) NOT NULL,
  `share_title` varchar(255) NOT NULL,
  `share_desc` varchar(255) NOT NULL,
  `title` varchar(200) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `sub_title` varchar(200) NOT NULL,
  `attention_url` varchar(200) NOT NULL,
  `loan_secret` varchar(255) DEFAULT NULL,
  `loan_appid` varchar(255) DEFAULT NULL,
  `attention_code` varchar(255) DEFAULT NULL,
  `rid` int(10) DEFAULT '0',
  `attention_code1` varchar(255) DEFAULT '0',
  `getip` tinyint(1) DEFAULT '0',
  `getip_addr` text NOT NULL COMMENT '���Ƶ���ip',
  `yc_url` varchar(255) DEFAULT NULL,
  `yc` tinyint(1) DEFAULT '0',
  `no_avatar` varchar(255) DEFAULT NULL,
  `gz_must` tinyint(1) DEFAULT NULL,
  `days` int(10) DEFAULT NULL,
  `person_code` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;");

pdo_query("CREATE TABLE IF NOT EXISTS ".tablename('wxz_wzb_roll_adv')." (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) DEFAULT NULL,
  `rid` int(10) DEFAULT NULL,
  `content` text,
  `type` tinyint(1) DEFAULT NULL,
  `dateline` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;");

pdo_query("CREATE TABLE IF NOT EXISTS ".tablename('wxz_wzb_red_packet')." (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  `mchid` varchar(100) NOT NULL DEFAULT '' COMMENT '�̻���',
  `password` varchar(2550) NOT NULL DEFAULT '' COMMENT '�̻�����',
  `appid` varchar(100) NOT NULL DEFAULT '' COMMENT '�����ID',
  `secret` varchar(255) NOT NULL DEFAULT '' COMMENT '�����secret',
  `ip` varchar(100) NOT NULL DEFAULT '' COMMENT '������IP',
  `sname` varchar(100) NOT NULL DEFAULT '' COMMENT '���ں�����',
  `wishing` varchar(100) NOT NULL DEFAULT '' COMMENT 'ף����',
  `actname` varchar(100) NOT NULL DEFAULT '' COMMENT '��������',
  `logo` varchar(255) NOT NULL DEFAULT '' COMMENT 'logo',
  `apiclient_cert` text COMMENT 'apiclient_cert',
  `apiclient_key` text COMMENT 'apiclient_key',
  `rootca` text COMMENT 'rootca',
  `createtime` int(11) unsigned NOT NULL DEFAULT '0',
  `min` int(10) DEFAULT '0',
  `max` int(10) DEFAULT '0',
  `type` tinyint(1) DEFAULT '0',
  `reward_amount_min` int(10) DEFAULT '0',
  `reward_amount_max` int(10) DEFAULT '0',
  `pool_amount` int(10) DEFAULT '0',
  `send_amount` int(10) DEFAULT '0',
  `packet_rule` text,
  `rid` int(10) DEFAULT '0',
  `withdraw_min` int(10) DEFAULT '100',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `rid` (`rid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;");

pdo_query("CREATE TABLE IF NOT EXISTS ".tablename('wxz_wzb_live_video_type')." (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type` int(10) DEFAULT NULL,
  `settings` text,
  `dateline` int(10) DEFAULT NULL,
  `rid` int(10) DEFAULT NULL,
  `uniacid` int(10) DEFAULT '0',
  `player_weight` int(10) DEFAULT '1280',
  `player_height` int(10) DEFAULT '720',
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`) USING BTREE,
  KEY `uniacid` (`uniacid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;");

pdo_query("CREATE TABLE IF NOT EXISTS ".tablename('wxz_wzb_money_log')." (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `rid` int(10) DEFAULT NULL,
  `uniacid` int(10) DEFAULT NULL,
  `uid` int(10) DEFAULT NULL,
  `amount` int(10) DEFAULT NULL,
  `type` tinyint(1) DEFAULT '0' COMMENT '1����2Ⱥ���3����4����',
  `dateline` int(10) DEFAULT NULL,
  `fromuid` int(10) DEFAULT NULL,
  `fromnickname` varchar(255) DEFAULT NULL,
  `fromheadimgurl` varchar(255) DEFAULT NULL,
  `fromid` int(10) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`) USING BTREE,
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;");

pdo_query("CREATE TABLE IF NOT EXISTS ".tablename('wxz_wzb_live_setting')." (
    `id` int(10) NOT NULL AUTO_INCREMENT,
  `sort` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `desc` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `is_auth` tinyint(1) DEFAULT NULL,
  `dateline` int(10) DEFAULT NULL,
  `start_at` int(10) DEFAULT NULL,
  `end_at` int(10) DEFAULT NULL,
  `uniacid` int(10) DEFAULT NULL,
  `rule` text,
  `images` varchar(255) DEFAULT NULL,
  `publisher` varchar(255) DEFAULT NULL,
  `reward` tinyint(1) DEFAULT '0',
  `base_num` int(10) DEFAULT '0',
  `num_float` int(10) DEFAULT '0',
  `total_num` int(10) DEFAULT '0',
  `float_type` tinyint(1) DEFAULT '0',
  `real_num` int(10) DEFAULT '0',
  `theme` varchar(255) DEFAULT NULL,
  `rid` int(10) DEFAULT '0',
  `limit` tinyint(10) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `delayed` int(10) DEFAULT NULL,
  `amount` int(10) DEFAULT NULL,
  `cid` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `img` varchar(300) NOT NULL,
  `isshow` int(11) NOT NULL,
  `player_height` int(11) NOT NULL DEFAULT '180',
  `button_show` tinyint(1) DEFAULT '0',
  `bgcolor` varchar(255) DEFAULT NULL,
  `color` varchar(255) DEFAULT NULL,
  `gn` varchar(255) DEFAULT NULL,
  `timecolor` varchar(255) DEFAULT NULL,
  `istruenum` tinyint(1) DEFAULT NULL,
  `recommend` tinyint(1) DEFAULT '0',
  `copyrightshow` tinyint(1) DEFAULT '0',
  `linkurl` text,
  `copyright` text,
  `islinkurl` tinyint(1) DEFAULT '0',
  `isallshutup` tinyint(1) DEFAULT '0',
  `style` tinyint(1) DEFAULT '1',
  `livestatus` tinyint(1) DEFAULT '0' COMMENT '1 即将直播 2直播中 3回播',
  `snposition` tinyint(3) unsigned DEFAULT '0',
  `livenum` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `is_auth` (`is_auth`) USING BTREE,
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `rid` (`rid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8;");

pdo_query("CREATE TABLE IF NOT EXISTS ".tablename('wxz_wzb_live_pic')." (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) DEFAULT NULL,
  `content` text,
  `publisher` varchar(255) DEFAULT NULL,
  `dateline` int(10) DEFAULT NULL,
  `images` varchar(255) DEFAULT NULL,
  `pic` text,
  `rid` int(10) DEFAULT '0',
  `title` varchar(255) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `rid` (`rid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8;");

pdo_query("CREATE TABLE IF NOT EXISTS ".tablename('wxz_wzb_live_menu')." (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(10) DEFAULT '0',
  `sort` int(11) NOT NULL,
  `uniacid` int(11) NOT NULL,
  `isshow` tinyint(1) NOT NULL,
  `type` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `settings` text NOT NULL,
  `dateline` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`) USING BTREE,
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `isshow` (`isshow`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;");

pdo_query("CREATE TABLE IF NOT EXISTS ".tablename('wxz_wzb_comment')." (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL,
  `uniacid` int(10) DEFAULT NULL,
  `content` text,
  `dateline` int(10) DEFAULT NULL,
  `is_auth` tinyint(1) DEFAULT '0',
  `nickname` varchar(255) DEFAULT NULL,
  `headimgurl` varchar(255) DEFAULT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `rid` int(10) DEFAULT '0',
  `lid` int(10) DEFAULT '0',
  `touid` int(10) DEFAULT '0',
  `tonickname` varchar(255) DEFAULT NULL,
  `toheadimgurl` varchar(255) DEFAULT NULL,
  `toid` int(10) DEFAULT '0',
  `isadmin` tinyint(1) DEFAULT '0',
  `ispacket` tinyint(1) DEFAULT '0',
  `amount` int(10) DEFAULT '0',
  `num` int(10) DEFAULT '0',
  `type` tinyint(1) DEFAULT '0',
  `send_num` int(10) DEFAULT '0',
  `yifa_amount` int(10) DEFAULT '0',
  `samount` text,
  `syifa` text,
  `dsid` int(10) DEFAULT '0',
  `dsstatus` int(1) DEFAULT '0',
  `dsamount` int(10) DEFAULT '0',
  `giftid` int(10) DEFAULT NULL,
  `giftnum` int(10) DEFAULT NULL,
  `giftpic` varchar(255) DEFAULT NULL,
  `giftstatus` tinyint(1) DEFAULT '0',
  `gid` int(10) DEFAULT '0',
  `groupid` int(10) DEFAULT '0',
  `groupamount` int(10) DEFAULT '0',
  `ispic` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `is_auth` (`is_auth`) USING BTREE,
  KEY `rid` (`rid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;");

pdo_query("CREATE TABLE IF NOT EXISTS ".tablename('wxz_wzb_category')." (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '�����ʺ�',
  `title` varchar(50) NOT NULL COMMENT '��������',
  `isshow` tinyint(1) NOT NULL DEFAULT '0' COMMENT '�Ƿ���ʾ',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '����',
  `dateline` int(11) NOT NULL,
  `pid` int(10) DEFAULT '0',
  `linkurl` text,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `isshow` (`isshow`) USING BTREE,
  KEY `sort` (`sort`) USING BTREE,
  KEY `pid` (`pid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;");

pdo_query("CREATE TABLE IF NOT EXISTS ".tablename('wxz_wzb_packet_log')." (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `fee` varchar(20) NOT NULL DEFAULT '',
  `status` varchar(255) NOT NULL,
  `dateline` int(10) DEFAULT NULL,
  `type` tinyint(1) DEFAULT NULL,
  `rid` int(10) DEFAULT '0',
  `fromid` int(10) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `uniacid` (`uniacid`),
  KEY `status` (`status`),
  KEY `rid` (`rid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=213125 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;");

pdo_query("CREATE TABLE IF NOT EXISTS ".tablename('wxz_wzb_paylog')." (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `rid` int(10) DEFAULT NULL,
  `uid` int(10) DEFAULT NULL,
  `lid` int(10) DEFAULT NULL,
  `amount` int(10) DEFAULT '0',
  `status` tinyint(1) DEFAULT '0',
  `type` tinyint(1) DEFAULT '0' COMMENT '1 ֱ���丶�� 2 ֱ�������',
  `dateline` int(10) DEFAULT NULL,
  `transid` varchar(255) DEFAULT NULL,
  `intotime` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE,
  KEY `status` (`status`) USING BTREE,
  KEY `uniacid` (`uniacid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=854 DEFAULT CHARSET=utf8;");

pdo_query("CREATE TABLE IF NOT EXISTS ".tablename('wxz_wzb_polling')." (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `rid` int(10) DEFAULT '0',
  `type` tinyint(1) DEFAULT '0' COMMENT '1����2���˽���3ȫ�����4���5����6���˴���7��������',
  `comment_id` int(10) DEFAULT '0',
  `pic_id` int(10) DEFAULT '0',
  `black_id` int(10) DEFAULT NULL,
  `live_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`) USING BTREE,
  KEY `type` (`type`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=210 DEFAULT CHARSET=utf8;");


pdo_query("CREATE TABLE IF NOT EXISTS ".tablename('wxz_wzb_list')." (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `title` varchar(200) NOT NULL,
  `list_share_img` varchar(255) NOT NULL,
  `list_share_title` varchar(255) NOT NULL,
  `list_share_desc` varchar(255) NOT NULL,
  `dateline` int(10) DEFAULT NULL,
  `tel` varchar(255) DEFAULT NULL,
  `cname` varchar(255) DEFAULT NULL,
  `caddress` varchar(255) DEFAULT NULL,
  `copyright` varchar(255) DEFAULT NULL,
  `style` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;");

pdo_query("CREATE TABLE IF NOT EXISTS ".tablename('wxz_wzb_help')." (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `share_uid` int(10) DEFAULT NULL,
  `help_uid` int(10) DEFAULT NULL,
  `dateline` int(10) DEFAULT NULL,
  `uniacid` int(10) DEFAULT NULL,
  `rid` int(10) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `share_uid` (`share_uid`) USING BTREE,
  KEY `help_uid` (`help_uid`) USING BTREE,
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `rid` (`rid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8;");

pdo_query("CREATE TABLE IF NOT EXISTS ".tablename('wxz_wzb_banner')." (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `img` varchar(255) DEFAULT NULL,
  `url` text,
  `isshow` tinyint(1) DEFAULT NULL,
  `uniacid` int(10) DEFAULT NULL,
  `dateline` int(10) DEFAULT NULL,
  `sort` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;");

pdo_query("CREATE TABLE IF NOT EXISTS ".tablename('wxz_wzb_ds')." (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `fee` varchar(20) NOT NULL DEFAULT '',
  `status` varchar(255) NOT NULL DEFAULT '0',
  `dateline` int(10) DEFAULT NULL,
  `rid` int(10) DEFAULT '0',
  `type` tinyint(1) DEFAULT '1' COMMENT '1���˴���2��������',
  `touid` int(10) DEFAULT '0',
  `tonickname` varchar(255) DEFAULT NULL,
  `toheadurl` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `uniacid` (`uniacid`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=213341 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;");

pdo_query("CREATE TABLE IF NOT EXISTS ".tablename('wxz_wzb_ds_setting')." (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `rid` int(10) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `settings` text,
  `dateline` int(10) DEFAULT NULL,
  `content` varchar(255) DEFAULT NULL,
  `isshow` tinyint(1) DEFAULT NULL,
  `nickname` varchar(255) DEFAULT NULL,
  `uid` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;");

pdo_query("CREATE TABLE IF NOT EXISTS ".tablename('wxz_wzb_gift')." (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `pic` varchar(255) DEFAULT NULL,
  `amount` int(10) DEFAULT NULL,
  `dateline` int(10) DEFAULT NULL,
  `rid` int(10) DEFAULT NULL,
  `isshow` tinyint(1) DEFAULT NULL,
  `uniacid` int(10) DEFAULT NULL,
  `sort` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`) USING BTREE,
  KEY `isshow` (`isshow`) USING BTREE,
  KEY `uniacid` (`uniacid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;");

pdo_query("CREATE TABLE IF NOT EXISTS ".tablename('wxz_wzb_giftlog')." (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `giftid` int(10) NOT NULL DEFAULT '0',
  `status` varchar(255) NOT NULL DEFAULT '0',
  `dateline` int(10) DEFAULT NULL,
  `rid` int(10) DEFAULT '0',
  `num` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `uniacid` (`uniacid`),
  KEY `status` (`status`),
  KEY `rid` (`rid`) USING BTREE,
  KEY `giftid` (`giftid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;");

pdo_query("CREATE TABLE IF NOT EXISTS ".tablename('wxz_wzb_grouppacket')." (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) DEFAULT NULL,
  `rid` int(10) DEFAULT NULL,
  `uniacid` int(10) DEFAULT NULL,
  `type` tinyint(1) DEFAULT NULL,
  `amount` int(10) DEFAULT NULL,
  `num` int(10) DEFAULT NULL,
  `json` text,
  `remark` varchar(255) DEFAULT NULL,
  `dateline` int(10) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`) USING BTREE,
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `status` (`status`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;");

pdo_query("CREATE TABLE IF NOT EXISTS ".tablename('wxz_wzb_grouppacket_log')." (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `hid` int(10) DEFAULT NULL,
  `rid` int(10) DEFAULT NULL,
  `uid` int(10) DEFAULT NULL,
  `uniacid` int(10) DEFAULT NULL,
  `comment_id` int(10) DEFAULT NULL,
  `amount` int(10) DEFAULT NULL,
  `dateline` int(10) DEFAULT NULL,
  `headimgurl` varchar(255) DEFAULT NULL,
  `nickname` varchar(255) DEFAULT NULL,
  `type` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;");


pdo_query("CREATE TABLE IF NOT EXISTS ".tablename('wxz_wzb_live_red_packet')." (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  `createtime` int(11) unsigned NOT NULL DEFAULT '0',
  `min` int(10) DEFAULT '0',
  `max` int(10) DEFAULT '0',
  `type` tinyint(1) DEFAULT '0',
  `reward_amount_min` int(10) DEFAULT '0',
  `reward_amount_max` int(10) DEFAULT '0',
  `pool_amount` int(10) DEFAULT '0',
  `send_amount` int(10) DEFAULT '0',
  `packet_rule` text,
  `rid` int(10) DEFAULT '0',
  `withdraw_min` int(10) DEFAULT '100',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `rid` (`rid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;");

pdo_query("CREATE TABLE IF NOT EXISTS ".tablename('wxz_wzb_wlive_setting')." (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `sort` int(10) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `total_num` int(10) DEFAULT NULL,
  `real_num` int(10) DEFAULT NULL,
  `islinkurl` tinyint(1) DEFAULT NULL,
  `linkurl` text,
  `isshow` tinyint(1) DEFAULT NULL,
  `cid` int(10) DEFAULT NULL,
  `dateline` int(10) DEFAULT NULL,
  `uniacid` int(10) DEFAULT NULL,
  `img` varchar(255) DEFAULT NULL,
  `type` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;");

pdo_query("CREATE TABLE IF NOT EXISTS ".tablename('wxz_wzb_notice_logs')." (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `openid` varchar(255) DEFAULT NULL,
  `uniacid` int(10) DEFAULT NULL,
  `rid` int(10) DEFAULT NULL,
  `tid` int(10) DEFAULT NULL,
  `status` text,
  `dateline` int(10) DEFAULT NULL,
  `nickname` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1002 DEFAULT CHARSET=utf8;");

pdo_query("CREATE TABLE IF NOT EXISTS ".tablename('wxz_wzb_notice')." (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `rid` int(10) DEFAULT NULL,
  `template_id` text,
  `images` varchar(255) DEFAULT NULL,
  `timer` int(10) DEFAULT NULL,
  `type` tinyint(1) DEFAULT '0',
  `dateline` int(10) DEFAULT NULL,
  `uniacid` int(10) DEFAULT NULL,
  `timeType` tinyint(1) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0',
  `url` varchar(255) DEFAULT NULL,
  `firstvalue` varchar(255) DEFAULT NULL,
  `firstcolor` varchar(255) DEFAULT NULL,
  `keyword1value` varchar(255) DEFAULT NULL,
  `keyword1color` varchar(255) DEFAULT NULL,
  `keyword2value` varchar(255) DEFAULT NULL,
  `keyword2color` varchar(255) DEFAULT NULL,
  `remarkvalue` varchar(255) DEFAULT NULL,
  `remarkcolor` varchar(255) DEFAULT NULL,
  `os` tinyint(1) DEFAULT NULL,
  `keyword3value` varchar(255) DEFAULT NULL,
  `keyword3color` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`) USING BTREE,
  KEY `uniacid` (`uniacid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;");

pdo_query("CREATE TABLE IF NOT EXISTS ".tablename('wxz_wzb_blacklist')." (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `rid` int(10) DEFAULT NULL,
  `uid` int(10) DEFAULT NULL,
  `type` tinyint(1) DEFAULT NULL COMMENT '1����2ȡ������',
  `dateline` int(10) DEFAULT NULL,
  `touid` int(10) DEFAULT NULL,
  `uniacid` int(10) DEFAULT NULL,
  `nickname` varchar(255) DEFAULT NULL,
  `headimgurl` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`) USING BTREE,
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `type` (`type`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;");
?>
