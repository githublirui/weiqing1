<?php

/*
 *  
 * 抽奖页面
 */
require_once WXZ_PANORAMA . '/function/global.func.php';
require_once WXZ_PANORAMA . '/source/Project.class.php';
require_once WXZ_PANORAMA . '/source/Activity.class.php';
include_once dirname(__FILE__) . '/permission.php';

global $_W, $_GPC;

$modulePath = '../addons/' . $_GPC['m'] . '/';
$_W['module_config'] = $this->module['config'];
$pid = $_GPC['pid'];

$next_pano = Project::getNextProject($aid, $pid);
$next_pano = $next_pano['id'];


$redirect = "{$_W['siteroot']}app/index.php?i={$_GPC['i']}&c=entry&do=index&m={$_GPC['m']}&aid={$aid}";

if (!$pid) {
    message('场景参数不能为空！', $redirect);
}

session_start();
if ($_SESSION['__:proxy:url_from'] != 'quanjing') {
    header("Location: {$_W['siteroot']}app/index.php?i={$_GPC['i']}&c=entry&do=quanjing&m={$_GPC['m']}&pid={$next_pano}&aid={$aid}");
    exit;
}

unset($_SESSION['__:proxy:url_from']);

//判断是否中奖，分享
$sql = "select * from " . tablename('wxz_panorama_win') . " where aid={$aid} AND fans_id =" . $user["uid"];
$wins = pdo_fetchall($sql, $pars);
$sql = "select share_num,cellphone from " . tablename('wxz_panorama_fans') . " where uid =" . $user["uid"];
$is_fans = pdo_fetch($sql, $pars);
//if ($is_win && $is_fans['share_num'] == '0' && $is_fans['cellphone']) {
//    $award_msg = $is_win['award'];
//    if ($is_win['award'] == '现金') {
//        $fee = $is_win['fee'] / 100;
//        $award_msg = "现金{$fee}元";
//    }
//    $show_msg = "<p>恭喜您获得了</p><p id='black'>" . $award_msg . " （您奖品ID为：" . $is_win['award_id'] . " ）</p><p>分享后可以领取</p>";
//    include $this->template(get_real_tpl('msg'));
//}
//
//判断用户是否中过奖
if ($wins && count($wins) >= $activityInfo['max_award_num']) {
    $show_msg = "<p>很可惜没中奖，前往下一个场景，找宝藏吧！<a href='{$_W['siteroot']}app/index.php?i={$_GPC['i']}&c=entry&do=quanjing&m={$_GPC['m']}&pid={$next_pano}&aid={$aid}'>点击进入下一个场景</a></p>";
    include $this->template(get_real_tpl('msg_fail'));
    die;
}

/**
 * 概率算法 
 * @param array $probability 
 * @return integer|string 
 */
function get_rand($probability) {
// 概率数组的总概率精度  
    $max = array_sum($probability);
    foreach ($probability as $key => $val) {
        $rand_number = mt_rand(1, $max); //从1到max中随机一个值  
        if ($rand_number <= $val) {//如果这个值小于等于当前中奖项的概率，我们就认为已经中奖  
            return $key;
        } else {
            $max -= $val; //否则max减去当前中奖项的概率，然后继续参与运算  
        }
    }
}

//奖品概率计算
//查询所有奖品
$sql = "SELECT * FROM " . tablename('wxz_panorama_award') . " WHERE aid={$aid} AND uniacid={$_GPC['i']} AND left_num>0";
$award_list = pdo_fetchall($sql, $pars);

foreach ($award_list as $award) {
    $prize_arr[] = array('id' => $award['id'], 'type' => $award['type'], 'min_money' => $award['min_money'], 'max_money' => $award['max_money'], 'left_num' => $award['left_num'], 'prize' => $award['name'], 'pro' => $award['probability']);
}

//计算未中奖概率
$totalProbability = array_sum(array_column($award_list, 'probability'));
$probabilityNum = array();
$probabilityNum = array_pad($probabilityNum, strlen($totalProbability) - 1, 0);
$probabilityN0 = '1' . implode('', $probabilityNum);
if ($probabilityN0 == $totalProbability) {
    $noPrizePro = 0;
} else {
    $probabilityN0 = '1' . implode('', $probabilityNum) . '0';
    $noPrizePro = $probabilityN0 - $totalProbability;
}

$prize_arr[] = array('id' => 0, 'left_num' => 0, 'prize' => '未中奖', 'pro' => $noPrizePro);
foreach ($prize_arr as $key => $val) {
    $probability[$key] = $val["pro"];
}

$award_n = get_rand($probability);
$award_type = $prize_arr[$award_n]["type"];
$award_min_money = $prize_arr[$award_n]["min_money"];
$award_max_money = $prize_arr[$award_n]["max_money"];
$award_name = $prize_arr[$award_n]["prize"];
$award_id = $prize_arr[$award_n]["id"];
$award_num = $prize_arr[$award_n]["left_num"];
$fee = 0;
$inventory = 1;

if ($award_type == 1) {
    if ($award_num < 100) {
        $award_id = 0;
        $award_name = '未中奖';
    } else {
        if ($award_num <= $award_min_money) {
            $fee = $award_min_money;
        } else {
            $fee = rand($award_min_money, $award_max_money);
        }
        $award_name = '现金' . ($fee / 100) . '元';
        $inventory = $fee;
    }
}
if ($award_id) {
    $data = array(
        "uniacid" => $user["uniacid"],
        "aid" => $aid,
        "fans_id" => $user["uid"],
        "award_id" => $award_id,
        "award" => $award_name,
        "fee" => $fee,
        "pano" => $pid,
        "create_time" => time(),
    );
    pdo_insert("wxz_panorama_win", $data);
    $sql = "update " . tablename('wxz_panorama_fans') . " set award_name='{$award_name}',award_num=award_num+1 where uid={$user['uid']}";
    pdo_query($sql);
    $sql = "update " . tablename('wxz_panorama_award') . " set left_num=left_num-" . $inventory . " where id={$award_id}";
    pdo_query($sql);
//    $show_msg = "<p>恭喜你，获得了" . $award_name . " 分享后可以领取</p>";
    if (!$is_fans['cellphone']) {
        include $this->template(get_real_tpl('input_msg'));
    } else {
        header('Location: ' . $this->createMobileUrl('win', array('aid' => $aid)));
        exit;
    }
//include $this->template('msg');
    die;
} else {
    $show_msg = "<p>很可惜没中奖，前往下一个场景，找宝藏吧！<a href='{$_W['siteroot']}app/index.php?i={$_GPC['i']}&c=entry&do=quanjing&m={$_GPC['m']}&pid={$next_pano}&aid={$aid}'>点击进入下一个场景</a></p>";
    include $this->template(get_real_tpl('msg_fail'));
    die;
}
?>
