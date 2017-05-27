<?php

/**
 * 全景红包打款
 */
define('NOW_PATH', dirname(__FILE__));
header("Content-type:text/html;charset=utf-8");
set_time_limit(0);

require_once NOW_PATH . "/communication.func.php";
require_once NOW_PATH . "/db.class.php";

$teabox_db = array(
    'hostname' => "rds6nwjxl6758cmm0fwdxpublic.mysql.rds.aliyuncs.com:3306",
    'username' => 'we706',
    'password' => 'male365qqcom',
    'database' => 'weiqing',
    'charset' => "utf8"
);
$db = new mysqldb($teabox_db);
$uniacid = 23;//活动ID
while (true) {
    $sql = "select * from wq_wxz_panorama_win where fee>0 AND is_send=0 AND uniacid={$uniacid}";
    $wins = $db->row_query($sql);
    foreach ($wins as $win) {
//判断用户是否已经输入手机号，分享
        $sql = "select * from wq_wxz_panorama_fans where uid={$win['fans_id']}";
        $quanjing_user = $db->row_query_one($sql);
        if (!$quanjing_user || !$quanjing_user['sub_openid'] || !$quanjing_user['cellphone'] || $quanjing_user['share_num'] <= 0) {
            continue;
        }

        if ($quanjing_user['username']) {
            //用户名重复三次不发送
	    $quanjing_user['username'] = addslashes($quanjing_user['username']);
            $count = $db->row_count('wq_wxz_panorama_fans', "uniacid={$uniacid} AND username='" . $quanjing_user['username'] . "'");
            if ($count >= 3) {
                $update_win = array(
                    'is_send' => 3, //刷奖用户
                );
                $db->row_update('wq_wxz_panorama_win', $update_win, "id={$win['id']}");
                continue;
            }
        }

        $fee = $win['fee'];
        //插入红包发放表
        $insert_arr = array(
            'uid' => $quanjing_user['uid'],
            'uniacid' => $quanjing_user['uniacid'],
            'openid' => $quanjing_user['openid'],
            'pano' => $win['pano'],
            'fee' => $fee,
            'created' => time(),
        );
        $db->row_insert("wq_wxz_panorama_payrecord", $insert_arr);
        $record_id = $db->insert_id();
        $re = send_fee($quanjing_user['openid'], $fee, $record_id);
        if ($re === true) {
            //付款成功
            $update_record_data = array(
                'completed' => 1,
                'pay_time' => time(),
            );
            $update_win = array(
                'is_send' => 1
            );
        } else {
            //付款失败
            $update_win = array(
                'is_send' => 0
            );

            //风险帐号
            if (strpos($re, '风险') !== false) {
                $update_win = array(
                    'is_send' => 2, //风险帐号
                );
            }
            
            //超过限制
            if (strpos($re, '超过限制') !== false) {
                $update_win = array(
                    'is_send' => 3, //超过限制
                );
            }
            $update_record_data = array('log' => $re);
        }
        $db->row_update('wq_wxz_panorama_payrecord', $update_record_data, "id={$record_id}");
        $db->row_update('wq_wxz_panorama_win', $update_win, "id={$win['id']}");
    }
    usleep(2000);
}
echo '执行完毕';
