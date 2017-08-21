<?php

if (!pdo_fieldexists('wxz_panorama_scene', 'treasures')) {
    pdo_query("ALTER TABLE " . tablename('wxz_panorama_scene') . " ADD `treasures` varchar(255) DEFAULT '';");
}

if (!pdo_fieldexists('wxz_panorama_award', 'type')) {
    pdo_query("ALTER TABLE " . tablename('wxz_panorama_award') . " ADD `type` tinyint(3) DEFAULT '1';");
}

if (!pdo_fieldexists('wxz_panorama_award', 'min_money')) {
    pdo_query("ALTER TABLE " . tablename('wxz_panorama_award') . " ADD `min_money` int(11) DEFAULT '0';");
}

if (!pdo_fieldexists('wxz_panorama_award', 'max_money')) {
    pdo_query("ALTER TABLE " . tablename('wxz_panorama_award') . " ADD `max_money` int(11) DEFAULT '0';");
}

if (!pdo_fieldexists('wxz_panorama_scene', 'preview')) {
    pdo_query("ALTER TABLE " . tablename('wxz_panorama_scene') . " ADD `preview` varchar(255) DEFAULT '';");
}
if (!pdo_fieldexists('wxz_panorama_scene', 'thumb')) {
    pdo_query("ALTER TABLE " . tablename('wxz_panorama_scene') . " ADD `thumb` varchar(255) DEFAULT '';");
}

//修改保存图片字段长度
if (pdo_fieldexists('wxz_panorama_scene', 'up')) {
    pdo_query("ALTER TABLE " . tablename('wxz_panorama_scene') . " MODIFY COLUMN `up` varchar(1255) DEFAULT '';");
}
if (pdo_fieldexists('wxz_panorama_scene', 'back')) {
    pdo_query("ALTER TABLE " . tablename('wxz_panorama_scene') . " MODIFY COLUMN `back` varchar(1255) DEFAULT '';");
}
if (pdo_fieldexists('wxz_panorama_scene', 'down')) {
    pdo_query("ALTER TABLE " . tablename('wxz_panorama_scene') . " MODIFY COLUMN `down` varchar(1255) DEFAULT '';");
}
if (pdo_fieldexists('wxz_panorama_scene', 'front')) {
    pdo_query("ALTER TABLE " . tablename('wxz_panorama_scene') . " MODIFY COLUMN `front` varchar(1255) DEFAULT '';");
}
if (pdo_fieldexists('wxz_panorama_scene', 'left')) {
    pdo_query("ALTER TABLE " . tablename('wxz_panorama_scene') . " MODIFY COLUMN `left` varchar(1255) DEFAULT '';");
}
if (pdo_fieldexists('wxz_panorama_scene', 'right')) {
    pdo_query("ALTER TABLE " . tablename('wxz_panorama_scene') . " MODIFY COLUMN `right` varchar(1255) DEFAULT '';");
}

//项目表
if (!pdo_fieldexists('wxz_panorama_scene', 'project_id')) {
    pdo_query("ALTER TABLE " . tablename('wxz_panorama_scene') . " ADD `project_id` int(11) DEFAULT 0;");
}
if (!pdo_tableexists('wxz_panorama_project')) {
    pdo_query("CREATE TABLE " . tablename('wxz_panorama_project') . " (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL COMMENT '',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '项目名称',
  `sort_order` int(10) NOT NULL DEFAULT 0 COMMENT '设置为默认展示',
  `update_at` varchar(255) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `create_at` varchar(255) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8;");
}

if (!pdo_tableexists('wxz_panorama_project_config')) {
    pdo_query("CREATE TABLE " . tablename('wxz_panorama_project_config') . " (
   `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL COMMENT '',
  `project_id` int(10) unsigned NOT NULL COMMENT '项目id',
  `skin` varchar(20) NOT NULL DEFAULT '' COMMENT '皮肤',
  `effect` varchar(10) NOT NULL DEFAULT '' COMMENT '特效',
  `sound` varchar(255) NOT NULL DEFAULT '' COMMENT '背景音乐',
  `treasure` varchar(255) NOT NULL DEFAULT '' COMMENT '宝藏图片',
  `treasure_num` tinyint(1) NOT NULL DEFAULT 1 COMMENT '宝藏数量',
  `autorotate` tinyint(1) NOT NULL DEFAULT 1 COMMENT '是否开启自动旋转',
  `default` tinyint(1) NOT NULL DEFAULT 0 COMMENT '设置为默认展示',
  `update_at` varchar(255) NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8;");
}


if (!pdo_tableexists('wxz_panorama_scene_hotspot')) {
    pdo_query("CREATE TABLE " . tablename('wxz_panorama_scene_hotspot') . " (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `uniacid` int(10) unsigned NOT NULL COMMENT '',
    `project_id` int(10) unsigned NOT NULL COMMENT '项目id',
    `scene_id` int(10) unsigned NOT NULL COMMENT '场景id',
    `type` tinyint(1) NOT NULL COMMENT '热点类型',
    `name` varchar(20) NOT NULL DEFAULT '' COMMENT '热点名称',
    `img` varchar(255) NOT NULL DEFAULT '' COMMENT '热点图片',
    `action` tinyint(1)  NOT NULL COMMENT '热点动作',
    `config` text COMMENT '配置详情',
    `update_at` INT(11) NOT NULL,
    `create_at` INT(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8;");
}

if (!pdo_fieldexists('wxz_panorama_project_config', 'config')) {
    pdo_query("ALTER TABLE " . tablename('wxz_panorama_project_config') . " ADD `config` text COMMENT '配置详情';");
}

if (!pdo_fieldexists('wxz_panorama_fans', 'sub_openid')) {
    pdo_query("ALTER TABLE " . tablename('wxz_panorama_fans') . " ADD `sub_openid` varchar(255) COMMENT '订阅号openid';");
}

if (!pdo_fieldexists('wxz_panorama_win', 'aid')) {
    pdo_query("ALTER TABLE " . tablename('wxz_panorama_win') . " ADD `aid` int(11) COMMENT '活动id';");
}

if (!pdo_fieldexists('wxz_panorama_award', 'aid')) {
    pdo_query("ALTER TABLE " . tablename('wxz_panorama_award') . " ADD `aid` int(11) COMMENT '活动id';");
}

if (!pdo_fieldexists('wxz_panorama_page', 'aid')) {
    pdo_query("ALTER TABLE " . tablename('wxz_panorama_page') . " ADD `aid` int(11) COMMENT '活动id';");
}

if (!pdo_fieldexists('wxz_panorama_project', 'aid')) {
    pdo_query("ALTER TABLE " . tablename('wxz_panorama_project') . " ADD `aid` int(11) COMMENT '活动id';");
}

if (!pdo_tableexists('wxz_panorama_activity')) {
    pdo_query("CREATE TABLE IF NOT EXISTS " . tablename('wxz_panorama_activity') . " (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '活动名称',
  `date_start` date NOT NULL COMMENT '活动开始时间',
  `date_end` date NOT NULL COMMENT '活动结束时间',
  `max_award_num` int(10) NOT NULL COMMENT '单个用户最大中奖次数',
  `verification_code` varchar(50) NOT NULL COMMENT '核销码',
  `force_follow` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否强制关注',
  `force_follow_url` varchar(255) NOT NULL DEFAULT '0' COMMENT '强制关注url',
  `update_time` int(10) NOT NULL COMMENT '活动修改时间',
  `create_time` int(10) NOT NULL COMMENT '活动创建时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
}

if (!pdo_tableexists('wxz_panorama_reply_setting')) {
    pdo_query("CREATE TABLE IF NOT EXISTS " . tablename('wxz_panorama_reply_setting') . " (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `uniacid` INT(11) NOT NULL,
  `aid` INT(11) NOT NULL,
  `rid` INT(11) NOT NULL,
  `title` VARCHAR(500) NOT NULL COMMENT '标题',
  `desc` TEXT NOT NULL COMMENT '描述',
  `img` VARCHAR(255) NOT NULL COMMENT '图片',
  `link` VARCHAR(255) NOT NULL COMMENT '链接地址',
  `update_at` INT(11) NOT NULL,
  `create_at` INT(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8;");
}