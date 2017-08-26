<?php

/* * ]
 * 添加专题视频
 */
global $_W, $_GPC;
$sid = $_GPC['sid'];
$sql = "SELECT * FROM " . tablename('wxz_openeye_page') . " ORDER BY `id` DESC";
$videos = pdo_fetchall($sql);

if (checksubmit()) {
    //字段验证, 并获得正确的数据$dat
    $data = array(
        'uniacid' => $_W['uniacid'],
        'special_id' => $sid,
        'page_id' => $_GPC['page_id'],
        'create_time' => time(),
    );
    if (pdo_insert('wxz_openeye_special_page', $data)) {
        message('添加成功', $this->createWebUrl('special_page_list', array('sid' => $sid)));
    } else {
        message('添加失败', $this->createWebUrl('special_page_add', array('sid' => $sid)));
    }
}
load()->web('tpl');
include $this->template('web/special_page_add');
?>
