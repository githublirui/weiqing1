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
  `sub_openid` varchar(255) DEFAULT NULL COMMENT '订阅号openid',
  `openid` varchar(255) DEFAULT NULL COMMENT '服务号openid',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `sub_openid` (`sub_openid`) USING BTREE,
  KEY `openid` (`openid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=142 DEFAULT CHARSET=utf8;");

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

pdo_query("CREATE TABLE IF NOT EXISTS ".tablename('wxz_wzb_roll_adv')." (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) DEFAULT NULL,
  `rid` int(10) DEFAULT NULL,
  `content` text,
  `type` tinyint(1) DEFAULT NULL,
  `dateline` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;");

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
  `role` tinyint(1) DEFAULT '0' COMMENT '0普通1主播2管理员',
  `uniacid` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`) USING BTREE,
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;");

pdo_query("CREATE TABLE IF NOT EXISTS ".tablename('wxz_wzb_money_log')." (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `rid` int(10) DEFAULT NULL,
  `uniacid` int(10) DEFAULT NULL,
  `uid` int(10) DEFAULT NULL,
  `amount` int(10) DEFAULT NULL,
  `type` tinyint(1) DEFAULT '0' COMMENT '1打赏2群红包3助力4分享',
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

pdo_query("CREATE TABLE IF NOT EXISTS ".tablename('wxz_wzb_paylog')." (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `rid` int(10) DEFAULT NULL,
  `uid` int(10) DEFAULT NULL,
  `lid` int(10) DEFAULT NULL,
  `amount` int(10) DEFAULT '0',
  `status` tinyint(1) DEFAULT '0',
  `type` tinyint(1) DEFAULT '0' COMMENT '1 直播间付费 2 直播间打赏',
  `dateline` int(10) DEFAULT NULL,
  `transid` varchar(255) DEFAULT NULL,
  `intotime` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE,
  KEY `status` (`status`) USING BTREE,
  KEY `uniacid` (`uniacid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=854 DEFAULT CHARSET=utf8;");

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


pdo_query("CREATE TABLE IF NOT EXISTS ".tablename('wxz_wzb_category')." (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `title` varchar(50) NOT NULL COMMENT '分类名称',
  `isshow` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否显示',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `dateline` int(11) NOT NULL,
  `pid` int(10) DEFAULT '0',
  `linkurl` text,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `isshow` (`isshow`) USING BTREE,
  KEY `sort` (`sort`) USING BTREE,
  KEY `pid` (`pid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;");

pdo_query("CREATE TABLE IF NOT EXISTS ".tablename('wxz_wzb_ds')." (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `fee` varchar(20) NOT NULL DEFAULT '',
  `status` varchar(255) NOT NULL DEFAULT '0',
  `dateline` int(10) DEFAULT NULL,
  `rid` int(10) DEFAULT '0',
  `type` tinyint(1) DEFAULT '1' COMMENT '1个人打赏2主播打赏',
  `touid` int(10) DEFAULT '0',
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

pdo_query("CREATE TABLE IF NOT EXISTS ".tablename('wxz_wzb_polling')." (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `rid` int(10) DEFAULT '0',
  `type` tinyint(1) DEFAULT '0' COMMENT '1评论2个人禁言3全体禁言4红包5礼物6个人打赏7主播打赏',
  `comment_id` int(10) DEFAULT '0',
  `pic_id` int(10) DEFAULT '0',
  `black_id` int(10) DEFAULT NULL,
  `live_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`) USING BTREE,
  KEY `type` (`type`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=210 DEFAULT CHARSET=utf8;");

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
  `type` tinyint(1) DEFAULT NULL COMMENT '1禁言2取消禁言',
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





if(!pdo_fieldexists('wxz_wzb_setting', 'attention_code')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_setting')." ADD `attention_code` varchar(255) DEFAULT '0';");
}

if(!pdo_fieldexists('wxz_wzb_setting', 'yc_url')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_setting')." ADD `yc_url` varchar(255) DEFAULT NULL;");
}

if(!pdo_fieldexists('wxz_wzb_setting', 'yc')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_setting')." ADD `yc` tinyint(1) DEFAULT '0';");
}

if(!pdo_fieldexists('wxz_wzb_setting', 'no_avatar')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_setting')." ADD `no_avatar` varchar(255) DEFAULT NULL;");
}

if(!pdo_fieldexists('wxz_wzb_setting', 'gz_must')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_setting')." ADD `gz_must` tinyint(1) DEFAULT NULL;");
}

if(!pdo_fieldexists('wxz_wzb_setting', 'person_code')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_setting')." ADD `person_code` varchar(255) DEFAULT NULL;");
}

if(!pdo_fieldexists('wxz_wzb_comment', 'rid')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_comment')." ADD `rid` int(10) DEFAULT '0';");
}

if(!pdo_fieldexists('wxz_wzb_comment', 'ispic')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_comment')." ADD `ispic` tinyint(1) DEFAULT '0';");
}

if(!pdo_fieldexists('wxz_wzb_comment', 'yifa_amount')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_comment')." ADD `yifa_amount` int(10) DEFAULT '0';");
}

if(!pdo_fieldexists('wxz_wzb_comment', 'touid')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_comment')." ADD `touid` int(10) DEFAULT '0';");
}

if(!pdo_fieldexists('wxz_wzb_comment', 'tonickname')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_comment')." ADD `tonickname` varchar(255) DEFAULT NULL;");
}

if(!pdo_fieldexists('wxz_wzb_comment', 'toheadimgurl')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_comment')." ADD `toheadimgurl` varchar(255) DEFAULT NULL;");
}

if(!pdo_fieldexists('wxz_wzb_comment', 'toid')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_comment')." ADD `toid` int(10) DEFAULT '0';");
}

if(!pdo_fieldexists('wxz_wzb_comment', 'isadmin')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_comment')." ADD `isadmin` tinyint(1) DEFAULT '0';");
}

if(!pdo_fieldexists('wxz_wzb_comment', 'ispacket')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_comment')." ADD `ispacket` tinyint(1) DEFAULT '0';");
}

if(!pdo_fieldexists('wxz_wzb_comment', 'amount')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_comment')." ADD `amount` int(10) DEFAULT '0';");
}

if(!pdo_fieldexists('wxz_wzb_comment', 'num')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_comment')." ADD `num` int(10) DEFAULT '0';");
}

if(!pdo_fieldexists('wxz_wzb_comment', 'type')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_comment')." ADD `type` tinyint(1) DEFAULT '0';");
}

if(!pdo_fieldexists('wxz_wzb_comment', 'send_num')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_comment')." ADD `send_num` int(10) DEFAULT '0';");
}

if(!pdo_fieldexists('wxz_wzb_live_pic', 'rid')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_live_pic')." ADD `rid` int(10) DEFAULT '0';");
}

if(!pdo_fieldexists('wxz_wzb_packet_log', 'rid')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_packet_log')." ADD `rid` int(10) DEFAULT '0';");
}

if(!pdo_fieldexists('wxz_wzb_packet_log', 'fromid')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_packet_log')." ADD `fromid` int(10) DEFAULT '0';");
}

if(!pdo_fieldexists('wxz_wzb_red_packet', 'withdraw_min')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_red_packet')." ADD `withdraw_min` int(10) DEFAULT '100';");
}

if(!pdo_fieldexists('wxz_wzb_red_packet', 'rid')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_red_packet')." ADD `rid` int(10) DEFAULT '0';");
}


if(!pdo_fieldexists('wxz_wzb_user', 'rid')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_user')." ADD `rid` int(10) DEFAULT '0';");
}

if(!pdo_fieldexists('wxz_wzb_setting', 'rid')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_setting')." ADD `rid` int(10) DEFAULT '0';");
}

if(!pdo_fieldexists('wxz_wzb_live_setting', 'rid')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_live_setting')." ADD `rid` int(10) DEFAULT '0';");
}

if(!pdo_fieldexists('wxz_wzb_live_setting', 'livestatus')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_live_setting')." ADD `livestatus` tinyint(1) DEFAULT '0' COMMENT '1 即将直播 2直播中 3回播';");
}

if(!pdo_fieldexists('wxz_wzb_live_setting', 'livenum')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_live_setting')." ADD `livenum` tinyint(3) unsigned DEFAULT '0';");
}

if(!pdo_fieldexists('wxz_wzb_live_setting', 'snposition')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_live_setting')." ADD `snposition` tinyint(3) unsigned DEFAULT '0';");
}

if(!pdo_fieldexists('wxz_wzb_share', 'rid')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_share')." ADD `rid` int(10) DEFAULT '0';");
}

if(!pdo_fieldexists('wxz_wzb_setting', 'getip')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_setting')." ADD `getip` tinyint(1) DEFAULT '0';");
}

if(!pdo_fieldexists('wxz_wzb_setting', 'days')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_setting')." ADD `days` int(10) DEFAULT '0';");
}

if(!pdo_fieldexists('wxz_wzb_setting', 'getip_addr')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_setting')." ADD `getip_addr` text NOT NULL COMMENT '限制地区ip';");
}

if(!pdo_fieldexists('wxz_wzb_live_setting', 'copyright')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_live_setting')." ADD `copyright` text;");
}

if(!pdo_fieldexists('wxz_wzb_live_setting', 'copyrightshow')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_live_setting')." ADD `copyrightshow` tinyint(1) DEFAULT '0';");
}


if(!pdo_fieldexists('wxz_wzb_live_setting', 'limit')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_live_setting')." ADD `limit` tinyint(10) DEFAULT NULL;");
}

if(!pdo_fieldexists('wxz_wzb_live_setting', 'button_show')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_live_setting')." ADD `button_show` tinyint(1) DEFAULT '0';");
}

if(!pdo_fieldexists('wxz_wzb_live_setting', 'bgcolor')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_live_setting')." ADD `bgcolor` varchar(255) DEFAULT NULL;");
}

if(!pdo_fieldexists('wxz_wzb_live_setting', 'color')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_live_setting')." ADD `color` varchar(255) DEFAULT NULL;");
}

if(!pdo_fieldexists('wxz_wzb_live_setting', 'timecolor')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_live_setting')." ADD `timecolor` varchar(255) DEFAULT NULL;");
}

if(!pdo_fieldexists('wxz_wzb_live_setting', 'istruenum')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_live_setting')." ADD `istruenum` tinyint(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('wxz_wzb_live_setting', 'password')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_live_setting')." ADD `password` varchar(255) DEFAULT NULL;");
}

if(!pdo_fieldexists('wxz_wzb_live_setting', 'delayed')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_live_setting')." ADD `delayed` int(10) DEFAULT NULL;");
}

if(!pdo_fieldexists('wxz_wzb_live_setting', 'amount')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_live_setting')." ADD `amount` int(10) DEFAULT NULL;");
}

if(!pdo_fieldexists('wxz_wzb_user', 'password')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_user')." ADD `password` text;");
}

if(!pdo_fieldexists('wxz_wzb_live_pic', 'title')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_live_pic')." ADD `title` varchar(255) DEFAULT '0';");
}
if(!pdo_fieldexists('wxz_wzb_live_setting', 'sort')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_live_setting')." ADD `sort` int(11) NOT NULL;");
}

if(!pdo_fieldexists('wxz_wzb_live_setting', 'sort')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_live_setting')." ADD `sort` int(11) NOT NULL;");
}

if(!pdo_fieldexists('wxz_wzb_live_setting', 'cid')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_live_setting')." ADD `cid` int(11) NOT NULL;");
}

if(!pdo_fieldexists('wxz_wzb_live_setting', 'status')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_live_setting')." ADD `status` tinyint(1) NOT NULL;");
}

if(!pdo_fieldexists('wxz_wzb_live_setting', 'img')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_live_setting')." ADD `img` varchar(300) NOT NULL;");
}

if(!pdo_fieldexists('wxz_wzb_live_setting', 'isshow')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_live_setting')." ADD `isshow` int(11) NOT NULL;");
}

if(!pdo_fieldexists('wxz_wzb_live_setting', 'player_height')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_live_setting')." ADD `player_height` int(11) NOT NULL DEFAULT '180';");
}

if(!pdo_fieldexists('wxz_wzb_live_setting', 'recommend')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_live_setting')." ADD `recommend` tinyint(1) DEFAULT '0';");
}

if(!pdo_fieldexists('wxz_wzb_live_setting', 'isallshutup')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_live_setting')." ADD `isallshutup` tinyint(1) DEFAULT '0';");
}

if(!pdo_fieldexists('wxz_wzb_live_setting', 'style')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_live_setting')." ADD `style` tinyint(1) DEFAULT '1';");
}

if(!pdo_fieldexists('wxz_wzb_comment', 'samount')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_comment')." ADD `samount` text;");
}

if(!pdo_fieldexists('wxz_wzb_comment', 'syifa')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_comment')." ADD `syifa` text;");
}



if(!pdo_fieldexists('wxz_wzb_category', 'pid')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_category')." ADD `pid` int(10) DEFAULT '0';");
}

if(!pdo_fieldexists('wxz_wzb_category', 'linkurl')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_category')." ADD `linkurl` text;");
}



if(!pdo_fieldexists('wxz_wzb_viewer', 'role')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_viewer')." ADD `role` tinyint(1) DEFAULT '0' COMMENT '0普通1主播2管理员';");
}

if(!pdo_fieldexists('wxz_wzb_viewer', 'uniacid')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_viewer')." ADD `uniacid` int(10) DEFAULT NULL;");
}

if(!pdo_fieldexists('wxz_wzb_list', 'style')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_list')." ADD `style` tinyint(1) DEFAULT NULL;");
}



if(!pdo_fieldexists('wxz_wzb_spread_adv', 'bgcolor')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_spread_adv')." ADD `bgcolor` varchar(255) DEFAULT NULL;");
}

if(!pdo_fieldexists('wxz_wzb_spread_adv', 'color')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_spread_adv')." ADD `color` varchar(255) DEFAULT NULL;");
}

if(!pdo_fieldexists('wxz_wzb_spread_adv', 'timecolor')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_spread_adv')." ADD `timecolor` varchar(255) DEFAULT NULL;");
}




if(!pdo_fieldexists('wxz_wzb_comment', 'dsid')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_comment')." ADD `dsid` int(10) DEFAULT '0';");
}

if(!pdo_fieldexists('wxz_wzb_comment', 'dsstatus')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_comment')." ADD `dsstatus` int(1) DEFAULT '0';");
}

if(!pdo_fieldexists('wxz_wzb_comment', 'dsamount')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_comment')." ADD `dsamount` int(10) DEFAULT '0';");
}





if(!pdo_fieldexists('wxz_wzb_live_video_type', 'player_weight')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_live_video_type')." ADD `player_weight` int(10) DEFAULT '1280';");
}

if(!pdo_fieldexists('wxz_wzb_live_video_type', 'player_height')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_live_video_type')." ADD `player_height` int(10) DEFAULT '720';");
}

if(!pdo_fieldexists('wxz_wzb_viewer', 'isshutup')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_viewer')." ADD `isshutup` tinyint(1) DEFAULT '0';");
}

if(!pdo_fieldexists('wxz_wzb_live_setting', 'islinkurl')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_live_setting')." ADD `islinkurl` tinyint(1) DEFAULT '0';");
}

if(!pdo_fieldexists('wxz_wzb_live_setting', 'linkurl')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_live_setting')." ADD `linkurl` text;");
}



if(!pdo_fieldexists('wxz_wzb_comment', 'giftid')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_comment')." ADD `giftid` int(10) DEFAULT NULL;");
}

if(!pdo_fieldexists('wxz_wzb_comment', 'giftnum')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_comment')." ADD `giftnum` int(10) DEFAULT NULL;");
}

if(!pdo_fieldexists('wxz_wzb_live_setting', 'gn')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_live_setting')." ADD `gn` varchar(255) DEFAULT NULL;");
}

if(!pdo_fieldexists('wxz_wzb_comment', 'giftpic')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_comment')." ADD `giftpic` varchar(255) DEFAULT NULL;");
}

if(!pdo_fieldexists('wxz_wzb_comment', 'giftstatus')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_comment')." ADD `giftstatus` tinyint(1) DEFAULT '0';");
}

if(!pdo_fieldexists('wxz_wzb_comment', 'gid')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_comment')." ADD `gid` int(10) DEFAULT '0';");
}

if(!pdo_fieldexists('wxz_wzb_comment', 'groupid')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_comment')." ADD `groupid` int(10) DEFAULT '0';");
}

if(!pdo_fieldexists('wxz_wzb_comment', 'groupamount')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_comment')." ADD `groupamount` int(10) DEFAULT '0';");
}

if(!pdo_fieldexists('wxz_wzb_ds', 'type')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_ds')." ADD `type` tinyint(1) DEFAULT '1' COMMENT '1个人打赏2主播打赏';");
}

if(!pdo_fieldexists('wxz_wzb_ds', 'touid')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_ds')." ADD `touid` int(10) DEFAULT '0';");
}

if(!pdo_fieldexists('wxz_wzb_ds', 'toheadurl')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_ds')." ADD `toheadurl` varchar(255) DEFAULT NULL;");
}

if(!pdo_fieldexists('wxz_wzb_ds', 'tonickname')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_ds')." ADD `tonickname` varchar(255) DEFAULT NULL;");
}

if(!pdo_fieldexists('wxz_wzb_ds_setting', 'nickname')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_ds_setting')." ADD `nickname` varchar(255) DEFAULT NULL;");
}

if(!pdo_fieldexists('wxz_wzb_ds_setting', 'uid')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_ds_setting')." ADD `uid` int(10) DEFAULT '0';");
}

if(!pdo_fieldexists('wxz_wzb_list', 'tel')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_list')." ADD `tel` varchar(255) DEFAULT NULL;");
}


if(!pdo_fieldexists('wxz_wzb_list', 'cname')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_list')." ADD `cname` varchar(255) DEFAULT NULL;");
}


if(!pdo_fieldexists('wxz_wzb_list', 'caddress')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_list')." ADD `caddress` varchar(255) DEFAULT NULL;");
}


if(!pdo_fieldexists('wxz_wzb_list', 'copyright')) {
    pdo_query("ALTER TABLE ".tablename('wxz_wzb_list')." ADD `copyright` varchar(255) DEFAULT NULL;");
}


if(!pdo_indexexists('wxz_wzb_banner', 'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_banner')." ADD INDEX `uniacid` (`uniacid`);");
}

if(!pdo_indexexists('wxz_wzb_blacklist', 'rid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_blacklist')." ADD INDEX `rid` (`rid`);");
}

if(!pdo_indexexists('wxz_wzb_blacklist', 'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_blacklist')." ADD INDEX `uniacid` (`uniacid`);");
}

if(!pdo_indexexists('wxz_wzb_blacklist', 'type')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_blacklist')." ADD INDEX `type` (`type`);");
}

if(!pdo_indexexists('wxz_wzb_category', 'isshow')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_category')." ADD INDEX `isshow` (`isshow`);");
}

if(!pdo_indexexists('wxz_wzb_category', 'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_category')." ADD INDEX `uniacid` (`uniacid`);");
}

if(!pdo_indexexists('wxz_wzb_category', 'sort')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_category')." ADD INDEX `sort` (`sort`);");
}

if(!pdo_indexexists('wxz_wzb_category', 'pid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_category')." ADD INDEX `pid` (`pid`);");
}

if(!pdo_indexexists('wxz_wzb_comment', 'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_comment')." ADD INDEX `uniacid` (`uniacid`);");
}

if(!pdo_indexexists('wxz_wzb_comment', 'is_auth')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_comment')." ADD INDEX `is_auth` (`is_auth`);");
}

if(!pdo_indexexists('wxz_wzb_comment', 'rid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_comment')." ADD INDEX `rid` (`rid`);");
}


if(!pdo_indexexists('wxz_wzb_ds', 'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_ds')." ADD INDEX `uniacid` (`uniacid`);");
}

if(!pdo_indexexists('wxz_wzb_ds', 'status')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_ds')." ADD INDEX `status` (`status`);");
}

if(!pdo_indexexists('wxz_wzb_ds', 'uid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_ds')." ADD INDEX `uid` (`uid`);");
}


if(!pdo_indexexists('wxz_wzb_ds', 'rid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_ds')." ADD INDEX `rid` (`rid`);");
}



if(!pdo_indexexists('wxz_wzb_gift', 'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_gift')." ADD INDEX `uniacid` (`uniacid`);");
}

if(!pdo_indexexists('wxz_wzb_gift', 'isshow')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_gift')." ADD INDEX `isshow` (`isshow`);");
}


if(!pdo_indexexists('wxz_wzb_gift', 'rid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_gift')." ADD INDEX `rid` (`rid`);");
}




if(!pdo_indexexists('wxz_wzb_giftlog', 'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_giftlog')." ADD INDEX `uniacid` (`uniacid`);");
}

if(!pdo_indexexists('wxz_wzb_giftlog', 'status')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_giftlog')." ADD INDEX `status` (`status`);");
}


if(!pdo_indexexists('wxz_wzb_giftlog', 'rid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_giftlog')." ADD INDEX `rid` (`rid`);");
}

if(!pdo_indexexists('wxz_wzb_giftlog', 'giftid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_giftlog')." ADD INDEX `giftid` (`giftid`);");
}

if(!pdo_indexexists('wxz_wzb_giftlog', 'uid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_giftlog')." ADD INDEX `uid` (`uid`);");
}


if(!pdo_indexexists('wxz_wzb_grouppacket', 'rid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_grouppacket')." ADD INDEX `rid` (`rid`);");
}

if(!pdo_indexexists('wxz_wzb_grouppacket', 'status')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_grouppacket')." ADD INDEX `status` (`status`);");
}

if(!pdo_indexexists('wxz_wzb_grouppacket', 'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_grouppacket')." ADD INDEX `uniacid` (`uniacid`);");
}



if(!pdo_indexexists('wxz_wzb_help', 'share_uid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_help')." ADD INDEX `share_uid` (`share_uid`);");
}


if(!pdo_indexexists('wxz_wzb_help', 'help_uid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_help')." ADD INDEX `help_uid` (`help_uid`);");
}

if(!pdo_indexexists('wxz_wzb_help', 'rid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_help')." ADD INDEX `rid` (`rid`);");
}

if(!pdo_indexexists('wxz_wzb_help', 'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_help')." ADD INDEX `uniacid` (`uniacid`);");
}

if(!pdo_indexexists('wxz_wzb_list', 'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_list')." ADD INDEX `uniacid` (`uniacid`);");
}


if(!pdo_indexexists('wxz_wzb_live_menu', 'rid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_live_menu')." ADD INDEX `rid` (`rid`);");
}

if(!pdo_indexexists('wxz_wzb_live_menu', 'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_live_menu')." ADD INDEX `uniacid` (`uniacid`);");
}

if(!pdo_indexexists('wxz_wzb_live_menu', 'isshow')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_live_menu')." ADD INDEX `isshow` (`isshow`);");
}



if(!pdo_indexexists('wxz_wzb_live_pic', 'rid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_live_pic')." ADD INDEX `rid` (`rid`);");
}

if(!pdo_indexexists('wxz_wzb_live_pic', 'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_live_pic')." ADD INDEX `uniacid` (`uniacid`);");
}


if(!pdo_indexexists('wxz_wzb_live_red_packet', 'rid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_live_red_packet')." ADD INDEX `rid` (`rid`);");
}

if(!pdo_indexexists('wxz_wzb_live_red_packet', 'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_live_red_packet')." ADD INDEX `uniacid` (`uniacid`);");
}



if(!pdo_indexexists('wxz_wzb_live_setting', 'is_auth')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_live_setting')." ADD INDEX `is_auth` (`is_auth`);");
}


if(!pdo_indexexists('wxz_wzb_live_setting', 'rid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_live_setting')." ADD INDEX `rid` (`rid`);");
}

if(!pdo_indexexists('wxz_wzb_live_setting', 'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_live_setting')." ADD INDEX `uniacid` (`uniacid`);");
}


if(!pdo_indexexists('wxz_wzb_live_video_type', 'rid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_live_video_type')." ADD INDEX `rid` (`rid`);");
}

if(!pdo_indexexists('wxz_wzb_live_video_type', 'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_live_video_type')." ADD INDEX `uniacid` (`uniacid`);");
}


if(!pdo_indexexists('wxz_wzb_money_log', 'uid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_money_log')." ADD INDEX `uid` (`uid`);");
}


if(!pdo_indexexists('wxz_wzb_money_log', 'rid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_money_log')." ADD INDEX `rid` (`rid`);");
}

if(!pdo_indexexists('wxz_wzb_money_log', 'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_money_log')." ADD INDEX `uniacid` (`uniacid`);");
}


if(!pdo_indexexists('wxz_wzb_notice', 'rid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_notice')." ADD INDEX `rid` (`rid`);");
}

if(!pdo_indexexists('wxz_wzb_notice', 'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_notice')." ADD INDEX `uniacid` (`uniacid`);");
}



if(!pdo_indexexists('wxz_wzb_packet_log', 'status')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_packet_log')." ADD INDEX `status` (`status`);");
}

if(!pdo_indexexists('wxz_wzb_packet_log', 'uid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_packet_log')." ADD INDEX `uid` (`uid`);");
}

if(!pdo_indexexists('wxz_wzb_packet_log', 'rid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_packet_log')." ADD INDEX `rid` (`rid`);");
}

if(!pdo_indexexists('wxz_wzb_packet_log', 'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_packet_log')." ADD INDEX `uniacid` (`uniacid`);");
}


if(!pdo_indexexists('wxz_wzb_paylog', 'status')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_paylog')." ADD INDEX `status` (`status`);");
}

if(!pdo_indexexists('wxz_wzb_paylog', 'uid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_paylog')." ADD INDEX `uid` (`uid`);");
}

if(!pdo_indexexists('wxz_wzb_paylog', 'rid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_paylog')." ADD INDEX `rid` (`rid`);");
}

if(!pdo_indexexists('wxz_wzb_paylog', 'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_paylog')." ADD INDEX `uniacid` (`uniacid`);");
}


if(!pdo_indexexists('wxz_wzb_polling', 'rid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_polling')." ADD INDEX `rid` (`rid`);");
}

if(!pdo_indexexists('wxz_wzb_polling', 'type')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_polling')." ADD INDEX `type` (`type`);");
}



if(!pdo_indexexists('wxz_wzb_red_packet', 'rid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_red_packet')." ADD INDEX `rid` (`rid`);");
}

if(!pdo_indexexists('wxz_wzb_red_packet', 'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_red_packet')." ADD INDEX `uniacid` (`uniacid`);");
}

if(!pdo_indexexists('wxz_wzb_setting', 'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_setting')." ADD INDEX `uniacid` (`uniacid`);");
}




if(!pdo_indexexists('wxz_wzb_share', 'share_uid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_share')." ADD INDEX `share_uid` (`share_uid`);");
}

if(!pdo_indexexists('wxz_wzb_share', 'rid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_share')." ADD INDEX `rid` (`rid`);");
}

if(!pdo_indexexists('wxz_wzb_share', 'help_uid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_share')." ADD INDEX `help_uid` (`help_uid`);");
}

if(!pdo_indexexists('wxz_wzb_share', 'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_share')." ADD INDEX `uniacid` (`uniacid`);");
}


if(!pdo_indexexists('wxz_wzb_tx', 'rid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_tx')." ADD INDEX `rid` (`rid`);");
}

if(!pdo_indexexists('wxz_wzb_tx', 'uid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_tx')." ADD INDEX `uid` (`uid`);");
}

if(!pdo_indexexists('wxz_wzb_tx', 'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_tx')." ADD INDEX `uniacid` (`uniacid`);");
}


if(!pdo_indexexists('wxz_wzb_user', 'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_user')." ADD INDEX `uniacid` (`uniacid`);");
}


if(!pdo_indexexists('wxz_wzb_user', 'rid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_user')." ADD INDEX `rid` (`rid`);");
}

if(!pdo_indexexists('wxz_wzb_user', 'sub_openid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_user')." ADD INDEX `sub_openid` (`sub_openid`);");
}

if(!pdo_indexexists('wxz_wzb_user', 'openid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_user')." ADD INDEX `openid` (`openid`);");
}



if(!pdo_indexexists('wxz_wzb_viewer', 'rid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_viewer')." ADD INDEX `rid` (`rid`);");
}

if(!pdo_indexexists('wxz_wzb_viewer', 'uid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_viewer')." ADD INDEX `uid` (`uid`);");
}

if(!pdo_indexexists('wxz_wzb_viewer', 'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('wxz_wzb_viewer')." ADD INDEX `uniacid` (`uniacid`);");
}