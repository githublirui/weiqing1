<?php

global $_GPC, $_W;
ini_set('post_max_size', '12M');
ini_set('upload_max_filesize', '10M');
error_reporting("0");

require_once WXZ_EASY_PAY . '/function/global.func.php';
require_once WXZ_EASY_PAY . "/pay/imagecuter.php";
require_once WXZ_EASY_PAY . '/pay/example/phpqrcode/phpqrcode.php';

load()->model('mc');
load()->func('communication');

$fan = mc_fansinfo($_W['openid']);
if (!empty($fan) && !empty($fan['openid'])) {
    $userinfo = $fan;
} else {
    $userinfo = mc_oauth_userinfo();
}

//$userinfo['uid'] = 0; //debug
        
if (empty($_GPC['goodsName'])) {
    message('产品名称不能为空');
}

//debug
//$_W['fans']['headimgurl'] = 'http://www.easyicon.net/api/resizeApi.php?id=1192335&size=128';
//$userinfo['uid'] = 0;

$p_img_url = uploadFile();

//删除多个的空数据
foreach ($_GPC['moreDesc'] as $k => $row) {
    if (!$_GPC['moreDesc'][$k] || !$_GPC['morePrice'][$k] || !$_GPC['moreStock'][$k]) {
        unset($_GPC['moreDesc'][$k], $_GPC['morePrice'][$k], $_GPC['moreStock'][$k]);
    }
}
foreach ($_GPC['morePostPrice'] as $k => $row) {
    if (!$_GPC['morePostPrice'][$k] || !$_GPC['moreCity'][$k]) {
        unset($_GPC['morePostPrice'][$k], $_GPC['moreCity'][$k]);
    }
}

//多种价格
//多种邮费添加到模版
$tplId = 0;
if (count($_GPC['morePostPrice']) > 0) {
    foreach ($_GPC['morePostPrice'] as $k => $morePostPrice) {
        $tplData[$_GPC['moreCity'][$k]] = $morePostPrice;
    }

    $desc = json_encode($tplData);
    $flag = md5($desc);
    $condition = "uid={$userinfo['uid']} AND flag='{$flag}'";
    $exists = pdo_get('wxz_easy_pay_post_tpl', $condition);
    if (!$exists) {
        //插入模版
        $tplInsert = array(
            'uniacid' => $_W['uniacid'],
            'uid' => $userinfo['uid'],
            'flag' => $flag,
            'postage' => $_GPC['more_post_price'],
            'goodsPostNum' => (int) $_GPC['post_num'],
            'desc' => $desc,
            'create_at' => time(),
        );
        $result = pdo_insert('wxz_easy_pay_post_tpl', $tplInsert);
        $tplId = pdo_insertid();
    } else {
        $tplId = $exists['id'];
    }
}

//添加商品
$pids = array();
$commData = array(
    'goodsName' => $_GPC['goodsName'],
    'goodsAtr' => $_GPC['goodsAtr'],
    'remark' => $_GPC['remark'],
    'promotion' => (int) $_GPC['promotion'] == 'on' ? 1 : 0,
    'postage' => $_GPC['post_price'],
    'goodsPostNum' => (int) $_GPC['post_num'],
    'goodsImg' => $p_img_url,
    'goodsSince' => $_GPC['goodsSince'] == 'on' ? 2 : 1,
    'goodsDetail' => $_GPC['goodsDetail'],
    'openid' => $userinfo['openid'],
    'nickname' => $userinfo['nickname'],
    'headimgurl' => $userinfo['headimgurl'],
    'add_time' => $userinfo['add_time'],
    'tpl_id' => $tplId,
    'uid' => $userinfo['uid']
);
if (count($_GPC['moreDesc']) > 0) {
    //多种价格
    foreach ($_GPC['moreDesc'] as $k => $moreDesc) {
        //将商品信息存入数据库
        $insertData = $commData;
        $insertData['goodsNameExt'] = $moreDesc;
        $insertData['goodsPrice'] = $_GPC['morePrice'][$k];
        $insertData['goodsStock'] = $_GPC['moreStock'][$k];
        $result = pdo_insert('hangyi_product', $insertData);
        $pid = pdo_insertid();
        if ($pid) {
            $pids[] = $pid;
            $pInfos[$pid] = $insertData;
        }
    }
} else {
    //将商品信息存入数据库
    $insertData = $commData;
    $insertData['goodsPrice'] = $_GPC['goodsPrice'];
    $insertData['goodsStock'] = $_GPC['goodsStock'];
    $result = pdo_insert('hangyi_product', $insertData);
    $pid = pdo_insertid();
    if ($pid) {
        $pids[] = $pid;
        $pInfos[$pid] = $insertData;
    }
}

//添加失败
if (!$pids) {
    message('商品信息添加失败');
}

$main_url = "../addons/wxz_easy_pay/images/buy.png";
$pay_path = "../addons/wxz_easy_pay/images/pay.png";

list($main_width, $main_height) = getimagesize($main_url);

$chanping = $p_img_url;
list($chanping_width, $chanping_height) = getimagesize($chanping);

//把产品放大或缩小和main一样的宽度
//echo imagecreatefromstring(file_get_contents($chanping));

$newwidth = $main_width;
$newheight = ($main_width / $chanping_width) * $chanping_height;


$main_source = imagecreatefrompng($main_url);
if ($newheight > 800) {
    $newheight = 800;
} else if ($newheight < 800) {
    //裁剪掉主图尺寸
    $cjwidth = 800 - $newheight;
    $target_width_c = $main_width;
    $target_height_c = $main_height - $cjwidth;
    $target_width_c_image = imagecreatetruecolor($main_width, $target_height_c);
    imagecopyresampled($target_width_c_image, $main_source, 0, 0, 0, $cjwidth, $main_width, $target_height_c, $main_width, $target_height_c);
}
if ($target_width_c_image) {
    $main_source = $target_width_c_image;
}

$bb = imageCropper($chanping, $newwidth, $newheight);
imagecopyresampled($main_source, $bb, 0, 0, 0, 0, $newwidth, $newheight, $newwidth, $newheight);

$uploadDir = IA_ROOT . '/' . $_W['config']['upload']['attachdir'] . '/wxz_easy_pay/' . date('Ymd') . '/';
$uploadUrl = $_W['siteroot'] . $_W['config']['upload']['attachdir'] . '/wxz_easy_pay/' . date('Ymd') . '/';

$text4 = '技术支持：微小智';
$setting = pdo_get('hangyi_hbset', array('uniacid' => $_W['uniacid']));
if ($setting['img_text']) {
    $text4 = $setting['img_text'];
}

$font = '../addons/wxz_easy_pay/font/msyhbd.ttf';
$font2 = '../addons/wxz_easy_pay/font/msyh.ttf';
$pid = $pids[0];

$url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/app" . $this->createMobileUrl('productdetail') . "&pid=" . $pid;
$url2 = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/app" . $this->createMobileUrl('ordershow') . "&orderid=" . $pid;
$url3 = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];

$url = str_replace("/app./index.php", "", $url);
$url2 = str_replace("/app./index.php", "", $url2);
$url3 = str_replace("app/index.php", "", $url3);

$codeimg = $path . "qrcode" . rand(1, 10) . time() . ".png";
QRcode::png($url, $codeimg, "L", 3.7);

$info = imagecreatefrompng($codeimg);

$info_pay = imagecreatefrompng($pay_path);

$response = ihttp_get($_W['fans']['headimgurl']);
$avatarFileName = $userinfo['uid'] . '_avatar' . ".jpg";
$head_path = $uploadDir . $avatarFileName;
file_put_contents($head_path, $response['content']);
$headimgurl = imageCropper($head_path, "65", "65");

imagecopyresampled($main_source, $info, 410, $newheight - 25, 0, 0, 165, 165, 165, 165);
imagecopyresampled($main_source, $info_pay, 110, $newheight + 14, 0, 0, 217, 65, 217, 65);
imagecopyresampled($main_source, $headimgurl, 30, $newheight + 15, 0, 0, 65, 65, 65, 65);
$text = "卖家 ：" . $_W['fans']['nickname'];

$formatPrice = number_format($pInfos[$pid]['goodsPrice'], 2);

$text2 = $formatPrice . " 元";
$text3 = $pInfos[$pid]['goodsName'];


$white = imagecolorallocate($main_source, 255, 255, 255);
$grey = imagecolorallocate($main_source, 0, 0, 0);
imagettftext($main_source, 20, 0, 30 + 2, $newheight - 20 + 2, $grey, $font2, $text);
imagettftext($main_source, 20, 0, 30, $newheight - 20, $white, $font2, $text);

imagettftext($main_source, 26, 0, 30, $newheight + 160, $white, $font, $text2);
imagettftext($main_source, 20, 0, 30, $newheight + 210, $white, $font, $text3);

//版权居中
$fontBox = imagettfbbox(18, 0, $font2, $text4);
imagettftext($main_source, 18, 0, ceil(($main_width - $fontBox[2]) / 2), $newheight + 295, $white, $font2, $text4);

ob_start();
imagejpeg($main_source, null, 100);
$img = ob_get_contents();
ob_end_clean();
$cur_time = time() . rand(1, 40);
$fileNmae = $cur_time . '.png';
$path = $uploadDir . $fileNmae;
$df = file_put_contents($path, $img);

if ($df) {
    $file_url = $uploadUrl . $fileNmae;
    //更新支付图片
    //更新批次号
    $result = pdo_query("UPDATE " . tablename('hangyi_product') . " SET `goodsCode`='" . $file_url . "', `batch_id`='" . $pid . "'  WHERE id in (" . implode(',', $pids) . ")");
    include $this->template('upimg');
}



