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

if (!pdo_fieldexists('wxz_panorama_scene', 'audio')) {
    pdo_query("ALTER TABLE " . tablename('wxz_panorama_scene') . " ADD `audio` varchar(255) DEFAULT '';");
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
  `update_at` varchar(255) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `create_at` varchar(255) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8;");
}

