<?php

if (!pdo_fieldexists('wxz_panorama_scene', 'treasures')) {
    pdo_query("ALTER TABLE " . tablename('wxz_panorama_scene') . " ADD `treasures` varchar(255) DEFAULT '';");
}