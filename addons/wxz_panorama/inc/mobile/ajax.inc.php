<?php

global $_W, $_GPC;
$do = (string) trim($_GPC['sdo']);
session_start();

/**
 * 执行动作
 */
require_once WXZ_PANORAMA . '/source/Fans.class.php';
require_once WXZ_PANORAMA . '/source/Activity.class.php';

$f = new Fans();
$openid = $_SESSION['__:proxy:openid'];
$fans = $f->getOne($openid, true);

if ($do == 'share') {
    require_once WXZ_PANORAMA . '/source/Fans.class.php';
    if ($fans) {
        $sql = "update " . tablename('wxz_panorama_fans') . " set share_num=share_num+1 where uid=" . $fans['uid'];
        $ret = pdo_query($sql);
        if ($ret) {
            //发放红包
            if ($fans['cellphone']) {
                $sql = "select id,fee,pano from " . tablename('wxz_panorama_win') . " where fee>0 AND is_send=0 AND uniacid={$_W['uniacid']} AND fans_id='{$fans['uid']}'";
                $wins = pdo_fetchall($sql);
                require_once WXZ_PANORAMA . '/quanjinghongbaopay/communication.func.php';
                foreach ($wins as $win) {
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
                    pdo_insert("wxz_panorama_payrecord", $insert_arr);
                    $record_id = pdo_insertid();
                    $re = send_fee($fans['openid'], $fee, $record_id);
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
                    pdo_update('wxz_panorama_payrecord', $update_record_data, array("id" => $record_id));
                    pdo_update('wxz_panorama_win', $update_win, array("id" => $win['id']));
                }
            }
        }
        echo "ok";
        exit;
    }
} else if ($do == 'regmsg') {
    $phone = $_POST['phone'];
    $username = $_POST['name'];
    $pattern = "/^((\d{3}-\d{8}|\d{4}-\d{7,8})|(1[3|5|7|8][0-9]{9}))$/";
    $s = preg_match($pattern, $phone);
    $user = $this->auth();
    if ($s == 0) {
        echo '手机号不正确';
        exit;
    }
    $ret = pdo_update('wxz_panorama_fans', array('cellphone' => $phone, 'username' => $username), array('uniacid' => $_GPC['i'], 'openid' => $user['openid']));
    if ($ret) {
        echo 'success';
    } else {
        echo 'error';
    }
} else if ($do == 'verification') {
    
    $aid = intval($_GPC['aid']);
    $activityInfo = Activity::getById($aid, 'verification_code');
    
    $settings = $this->module['config'];
    $id = $_GPC['id'];
    $code = $_GPC['code'];
    if ($code != $activityInfo['verification_code']) {
        echo '核销码错误';
        die;
    }

    $ret = pdo_update('wxz_panorama_win', array('is_send' => 1), array('uniacid' => $_GPC['i'], 'id' => $id, 'fans_id' => $fans['uid']));
    if ($ret) {
        echo 'ok';
    } else {
        echo '核销失败';
    }
}
?>
