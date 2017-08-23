<?php

global $_W, $_GPC;

$filters = array();
$filters['uniacid'] = $_W['uniacid'];

$filters['nickname'] = $_GPC['nickname'];

require_once WXZ_PANORAMA . '/source/Page.class.php';
$pageTypes = Page::getPageTypes();

$types = array_keys($pageTypes);
$typeStr = implode(',', $types);

Page::initPages();

$pindex = intval($_GPC['page']);
$pindex = max($pindex, 1);
$psize = 15;

$start = ($pindex - 1) * $psize;
$condition = "`uniacid`={$_W['uniacid']} AND `type` in ({$typeStr}) AND isdel=0";

$sql = "SELECT count(*) as num FROM " . tablename('wxz_panorama_page') . " WHERE {$condition}";
$total = pdo_fetchcolumn($sql);

$sql = "SELECT * FROM " . tablename('wxz_panorama_page') . " WHERE {$condition} limit $start , $psize";
$list = pdo_fetchall($sql);
$pager = pagination($total, $pindex, $psize);

include $this->template('web/page_list');
?>
