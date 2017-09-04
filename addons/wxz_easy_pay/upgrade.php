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