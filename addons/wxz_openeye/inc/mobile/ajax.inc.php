<?php

global $_W, $_GPC;
$do = (string) trim($_GPC['sdo']);
session_start();

/**
 * 执行动作
 */
require_once WXZ_OPENEYE . '/source/Fans.class.php';
$f = new Fans();
$openid = $_SESSION['__:proxy:openid'];
$fans = $f->getOne($openid, true);

if ($do == 'like') {
    $pageId = $_GPC['page_id'];
    //点赞
    //判断用户是否点赞
    $sql = "SELECT COUNT(*) FROM " . tablename('wxz_openeye_like') . " WHERE `uniacid`={$_W['uniacid']} AND `page_id`={$pageId} AND fans_id={$fans['uid']}";
    $count = pdo_fetchcolumn($sql);
    if ($count) {
        echo '已点赞';
        die;
    }
    //更新视频点赞数量
    $update_mem = "UPDATE  " . tablename('wxz_openeye_page') . " set like=like+1 where id={$pageId}"; //消耗积分
    $ret = pdo_query($update_mem);

    $insert_data = array(
        'uniacid' => $_W['uniacid'],
        'fans_id' => $fans['uid'],
        'page_id' => $pageId,
        'create_time' => time(),
    );
    pdo_insert('wxz_openeye_like', $insert_data);
    echo '点赞成功';
} else if ($do == 'comment') {
    $pageId = $_GPC['page_id'];
    $content = $_GPC['content'];
    //插入评论
    $insert_data = array(
        'uniacid' => $_W['uniacid'],
        'fans_id' => $fans['uid'],
        'page_id' => $pageId,
        'content' => $content,
        'create_time' => time(),
    );
    pdo_insert('wxz_openeye_comment', $insert_data);
    echo '评论成功';
}
?>
