<?php

global $_GPC, $_W;
ini_set('post_max_size', '12M');
ini_set('upload_max_filesize', '10M');
error_reporting("0");

require_once WXZ_EASY_PAY . '/function/global.func.php';
require_once WXZ_EASY_PAY . "/pay/imagecuter.php";
require_once WXZ_EASY_PAY . '/pay/example/phpqrcode/phpqrcode.php';

load()->model('mc');


$fan = mc_fansinfo($_W['openid']);
if (!empty($fan) && !empty($fan['openid'])) {
    $userinfo = $fan;
} else {
    $userinfo = mc_oauth_userinfo();
}

if (empty($_GPC['goodsName'])) {
    message('产品名称不能为空');
}


$p_img_url = uploadFile();

//删除默认
unset($_GPC['moreDesc'][0], $_GPC['morePrice'][0], $_GPC['moreStock'][0]);
unset($_GPC['morePostPrice'][0], $_GPC['moreCity'][0]);

//多种价格
//多种邮费添加到模版
$tplId = 0;
if (count($_GPC['morePostPrice']) > 0) {
    foreach ($_GPC['morePostPrice'] as $k => $morePostPrice) {
        $tplData[$_GPC['morePostPrice'][$k]] = $morePostPrice;
    }

    //插入模版
    $tplInsert = array(
        'uniacid' => $_W['uniacid'],
        'postage' => $_GPC['post_price'],
        'goodsPostNum' => (int) $_GPC['post_num'],
        'desc' => json_encode($tplData),
        'create_at' => time(),
    );

    $result = pdo_insert('wxz_easy_pay_page', $tplInsert);
    $tplId = pdo_insertid();
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
    'goodsInfo' => $_GPC['goodsInfo'],
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
        }
    }
} else {
    //将商品信息存入数据库
    $insertData = $commData;
    $insertData['goodsPrice'] = $_GPC['morePrice'][$k];
    $insertData['goodsStock'] = $_GPC['moreStock'][$k];
    $result = pdo_insert('hangyi_product', $insertData);
    $pid = pdo_insertid();
    if ($pid) {
        $pids[] = $pid;
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

load()->func('communication');

$response = ihttp_get($_W['fans']['headimgurl']);

$head_path = "../addons/wxz_easy_pay/upload/head" . time() . ".jpg";
file_put_contents($head_path, $response['content']);
$headimgurl = imageCropper($head_path, "65", "65");
//list($info_width, $info_height) = getimagesize($info);

imagecopyresampled($main_source, $info, 410, $newheight - 25, 0, 0, 165, 165, 165, 165);
imagecopyresampled($main_source, $info_pay, 110, $newheight + 14, 0, 0, 217, 65, 217, 65);
imagecopyresampled($main_source, $headimgurl, 30, $newheight + 15, 0, 0, 65, 65, 65, 65);
$text = "卖家 ：" . $_W['fans']['nickname'];

$text2 = $_GPC['goodsPrice'] . " 元";
if ($_GPC['goodsUnit']) {
    $text2 = $_GPC['goodsPrice'] . " 元 / " . $_GPC['goodsUnit'];
}
$text3 = $_GPC['goodsName'];
$text4 = '技术支持：微小智';
$font = '../addons/wxz_easy_pay/font/msyhbd.ttf';
$font2 = '../addons/wxz_easy_pay/font/msyh.ttf';
$white = imagecolorallocate($main_source, 255, 255, 255);
$grey = imagecolorallocate($main_source, 0, 0, 0);
imagettftext($main_source, 20, 0, 30 + 2, $newheight - 20 + 2, $grey, $font2, $text);
imagettftext($main_source, 20, 0, 30, $newheight - 20, $white, $font2, $text);

imagettftext($main_source, 26, 0, 30, $newheight + 160, $white, $font, $text2);
imagettftext($main_source, 20, 0, 30, $newheight + 210, $white, $font, $text3);


$setting = pdo_get('hangyi_hbset', array('uniacid' => $_W['uniacid']));
if ($setting['img_text']) {
    $text4 = $setting['img_text'];
}
//版权居中
$fontBox = imagettfbbox(18, 0, $font2, $text4);
//ceil(($width - $fontBox[2]) / 2)
//imagettftext($main_source, 18, 0, 120, $newheight+295, $white, $font2, $text4);
imagettftext($main_source, 18, 0, ceil(($main_width - $fontBox[2]) / 2), $newheight + 295, $white, $font2, $text4);


ob_start();
imagejpeg($main_source, null, 100);
$img = ob_get_contents();
ob_end_clean();
$cur_time = time() . rand(1, 40);
$df = file_put_contents("../addons/wxz_easy_pay/upload/" . $cur_time . '.png', $img);


if ($df) {
    $file_url = "../addons/wxz_easy_pay/upload/" . $cur_time . '.png';
    //更新支付图片
    $result = pdo_query("UPDATE " . tablename('hangyi_product') . " SET `goodsCode`='" . $file_url . "'  WHERE id = '" . $pid . "' limit 1");
    include $this->template('upimg');
}							
									
	

			