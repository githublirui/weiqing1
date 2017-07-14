<?php

/**
 * 用户注册
 */
global $_W, $_GPC;
include dirname(__FILE__) . '/permission.php';
$modulePublic = '../addons/' . $_GPC['m'] . '/static/';
if ($user['mobile'] && !$user['birthday']) {
    header('Location: ' . $this->createMobileUrl('reg2'));
    exit;
}
if ($user['mobile']) {
    header('Location: ' . $this->createMobileUrl('index'));
    exit;
}

session_start();
$code = $_SESSION['wxz_shoppingmall_reg_invite_code'];

//是否有邀请码
if (!$code) {
    message('请先输入邀请码', $this->createMobileUrl('reg_invite'));
}

$sql = "SELECT isuse FROM " . tablename('wxz_shoppingmall_invite_code') . " WHERE uniacid='{$_W['uniacid']}' AND `code`='{$code}'";
$codeInfo = pdo_fetch($sql);

if (!$codeInfo) {
    message('邀请码不存在，请重新输入', $this->createMobileUrl('reg_invite'));
}
if ($codeInfo['isuse'] == 2) {
    message('邀请码已使用', $this->createMobileUrl('reg_invite'));
}

require_once WXZ_SHOPPINGMALL . '/source/Page.class.php';
$pageContents = Page::getPage(array(7));

//地区选择
$addresses = array(
    '沙嘴街道办事处' =>
    array(
        0 => '沙嘴居委会',
        1 => '笆篓湾居委会',
        2 => '十一墩居委会',
        3 => '杜柳居委会',
        4 => '九十墩村',
        5 => '周榨村',
        6 => '骑尾村',
        7 => '梅湖村',
        8 => '金台村',
        9 => '万厢垸村',
        10 => '杨岗村',
        11 => '余吉村',
        12 => '徐台村',
        13 => '十全垸渔场村',
        14 => '刘口村',
        15 => '绿湾村',
        16 => '叶河村',
        17 => '王市口居委会',
    ),
    '干河街道办事处' =>
    array(
        0 => '大洪社区袁市居委会',
        1 => '复洲花园居委会',
        2 => '德政园居委会',
        3 => '干河四居委会',
        4 => '军垦路居委会',
        5 => '好义街居委会',
        6 => '石码头居委会',
        7 => '建设街居委会',
        8 => '大洪小区沔阳路居委会',
        9 => '船厂居委会',
        10 => '新河居委会',
        11 => '袁市村',
        12 => '楼子台村',
        13 => '窑台村',
        14 => '周廖村',
        15 => '幺湾村',
        16 => '王老村',
        17 => '三桥村',
        18 => '八湾村',
        19 => '许坝村',
        20 => '石桥村',
        21 => '西河村',
        22 => '五丰村',
        23 => '郑仁村',
        24 => '杂八村',
        25 => '观音堂村',
        26 => '小寺垸村',
        27 => '小南村',
        28 => '高家渡村',
        29 => '肖台村',
        30 => '龚台村',
        31 => '小林村',
        32 => '南坝村',
        33 => '中岭村',
        34 => '欧湾村',
        35 => '双龙村',
        36 => '熊湾村',
        37 => '',
    ),
    '龙华山办事处' =>
    array(
        0 => '解放街居委会',
        1 => '华山里居委会',
        2 => '油榨湾居委会',
        3 => '流潭居委会',
        4 => '新生居委会',
        5 => '钱沟居委会',
        6 => '何李居委会',
        7 => '何湾居委会',
        8 => '黄荆居委会',
        9 => '黄荆村',
        10 => '杜台村',
        11 => '打字号村',
        12 => '蔡帮村',
        13 => '肖阳村',
        14 => '汤郭村',
        15 => '黄林村',
        16 => '纱帽村',
        17 => '昌湾村',
        18 => '丁刘村',
        19 => '陈帮村',
        20 => '叶王村',
    ),
);
include $this->template('reg');
?>

