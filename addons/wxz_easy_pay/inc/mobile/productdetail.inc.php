<?php

//print_R($_FILES);
global $_GPC, $_W;
load()->model('mc');
$fan = mc_fansinfo($_W['openid']);
if (!empty($fan) && !empty($fan['openid'])) {
    $userinfo = $fan;
} else {
    $userinfo = mc_oauth_userinfo();
}

$pid = (int) $_GPC['pid'];
$uniacid = $_W['uniacid'];
$setting_font = pdo_get('hangyi_add_font', array('uniacid' => $uniacid));

$products = pdo_getall('hangyi_product', array('batch_id' => $pid));
$product = $products[0];
$sell_info = pdo_get('hangyi_user', array('uid' => $product['uid']));
$buy_info = pdo_get('hangyi_user', array('uid' => $userinfo['uid']));


if ($_GPC['ajax']) {
    $buyer_name = $_GPC['buyer_name'];
    $cell = $_GPC['cell'];
    $weixin = $_GPC['weixin'];
    $address = $_GPC['address'];
    $city = trim($_GPC['city']);
    $pids = $_GPC['pids'];
    $goodsNums = $_GPC['goodsNums'];
    $postscript = $_GPC['postscript'];
    $sell_openid = $product['openid'];
    $sell_id = $product['uid'];

    //多价格
    $buyPids = array();
    $buyGoodsNums = array();
    $totalGoodsNum = array_sum($goodsNums);
    $totalGoodsPrice = 0;
    $totalGoodsPriceReal = 0;
    $citys = explode(' ', $city);

    foreach ($goodsNums as $k => $goodsNum) {
        $goodsNum = (int) $goodsNum;
        if ($goodsNum > 0) {
            //计算商品价格
            $productInfo = pdo_get('hangyi_product', array('id' => $pids[$k]));
            if ($productInfo) {
                $buyPids[] = $pids[$k];
                $buyGoodsNums[] = $goodsNums[$k];
                $totalGoodsPrice += $productInfo['goodsPrice'] * 100 * $goodsNum;
            }
        }
    }

    //计算邮费
    $totalGoodsPriceReal = $totalGoodsPrice;
    $totalGoodsPrice = $totalGoodsPrice / 100;
    if ($product['goodsPostNum'] > $totalGoodsNum) {
        //获取邮费
        $postage = $product['postage'] * 100;
        if ($product['tpl_id']) {
            $info = pdo_get('wxz_easy_pay_post_tpl', "id='{$product['tpl_id']}'");
            $info['desc'] = json_decode($info['desc'], true);
            if (isset($info['desc'][$citys[0]])) {
                $postage = $info['desc'][$citys[0]] * 100;
            }
        }
        $totalGoodsPriceReal += $postage;
    }

    $totalGoodsPriceReal = $totalGoodsPriceReal / 100;

    //生成订单
    $dat['set'] = array(
        'uniacid' => $_W['uniacid'],
        'buy_openid' => $userinfo['openid'],
        'add_time' => time(),
        'price' => $product['goodsPrice'],
        'sell_id' => $sell_id,
        'buy_id' => $userinfo['uid'],
        'buy_nick' => $buyer_name,
        'cell' => $cell,
        'weixin' => $weixin,
        'city' => $city,
        'address' => $address,
        'goodsName' => $product['goodsName'],
        'goodsNum' => $totalGoodsNum,
        'pay_status' => 1,
        'order_status' => 1,
        'pid' => $pid,
        'pids' => implode(',', $buyPids),
        'goodsNums' => implode(',', $buyGoodsNums),
        'postscript' => $postscript,
        'sell_openid' => $sell_openid,
        'buy_nickname' => $userinfo['nickname'],
        'sell_nickname' => $sell_info['nickname'],
        'goodsPriceTotalReal' => $totalGoodsPriceReal,
        'goodsPriceTotal' => $totalGoodsPrice,
    );

    $insertorder = pdo_insert('hangyi_order', $dat['set']);
    $oid = pdo_insertid();
    //更新用户地址
    $user_data = array(
        'realname' => $buyer_name,
        'tel' => $cell,
        'weixin' => $weixin,
        'order_address' => $address,
        'city' => $city,
    );
    $result = pdo_update('hangyi_user', $user_data, array('uid' => $buy_info['uid']));

    echo $oid;
    exit();
}

if ($products) {
    $realname = $buy_info['realname'];
    $tel = $buy_info['tel'];
    $order_address = $buy_info['order_address'];
    $weixin = $buy_info['weixin'];
    $city = $buy_info['city'];

    include $this->template('product_pay');
}
?>


