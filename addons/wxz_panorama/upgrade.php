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