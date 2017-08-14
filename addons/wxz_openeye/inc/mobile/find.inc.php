<?php

/*
 * 
 * 首页
 */
global $_W, $_GPC;
$modulePath = '../addons/' . $_GPC['m'] . '/static/';

//获取图片域名
setting_load('remote');
if ($_W['setting']['remote']['type']) {
    $attach_url = $_W['attachurl_remote'];
} else {
    $attach_url = $_W['siteroot'] . $_W['config']['upload']['attachdir'];
}

$user = $this->auth();

//获取视频列表
//热门
$condition = "`uniacid`={$_W['uniacid']} AND page='hot'";
$sql = "SELECT * FROM " . tablename('wxz_openeye_page_position') . " WHERE {$condition} ORDER BY `order` DESC";
$listHot = pdo_fetchall($sql, $pars);
foreach ($listHot as $k => $row) {
    $page_info_sql = "SELECT * FROM " . tablename('wxz_openeye_page') . " WHERE id={$row['page_id']}";
    $page_info = pdo_fetch($page_info_sql);
    $listHot[$k] = array_merge($page_info, $row);
}

//分类
$condition = "`uniacid`={$_W['uniacid']} AND page='category'";
$sql = "SELECT * FROM " . tablename('wxz_openeye_page_position') . " WHERE {$condition} ORDER BY `order` DESC";
$listCategory = pdo_fetchall($sql, $pars);
foreach ($listCategory as $k => $row) {
    $page_info_sql = "SELECT * FROM " . tablename('wxz_openeye_page') . " WHERE id={$row['page_id']}";
    $page_info = pdo_fetch($page_info_sql);
    $listCategory[$k] = array_merge($page_info, $row);
}

$resultHot = array();
$resultCategory = array();

foreach ($listHot as $row) {
    if ($row['page_type'] == 'banner') {
        //根据分类分组
        $resultHot[$row['page_type']][] = $row; //列表类型
    } else {
        if ($row['page_type'] == 'list') {
            if (!isset($resultHot['list1'])) {
                $resultHot['list1'][$row['category']][] = $row;
            } else {
                $resultHot['list2'][$row['category']][] = $row;
            }
        } else {
            $resultHot[$row['page_type']][$row['category']][] = $row;
        }
    }
}


foreach ($listCategory as $row) {
    $resultCategory[$row['page_type']][$row['category']][] = $row;
    if ($row['category_desc']) {
        $category_descs[$row['category']] = $row['category_desc'];
    }
}

include $this->template('find');
?>
