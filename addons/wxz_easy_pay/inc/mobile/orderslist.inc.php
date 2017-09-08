<?php

//print_R($_FILES);
global $_GPC, $_W;
$uniacid = $_W['uniacid'];
load()->model('mc');
$fan = mc_fansinfo($_W['openid']);
if (!empty($fan) && !empty($fan['openid'])) {
    $userinfo = $fan;
} else {
    $userinfo = mc_oauth_userinfo();
}

$uid = $userinfo['uid'];
//$uid = 0;//debug
$search = $_GPC['search'];
if ($_GPC['ac'] == 'ajaxdele') {

    $orderinfo = pdo_get('hangyi_order', array('id' => $_GPC['id'], 'sell_id' => $userinfo['uid']));
    if ($orderinfo['id']) {
        $re = pdo_query("DELETE FROM " . tablename('hangyi_order') . "  WHERE id = '" . $orderinfo['id'] . "' limit 1");
        if ($re) {
            echo 1;
        }
    }
    exit();
}
if ($_GPC['ac'] == 'ajaxsend') {
    $orderinfo = pdo_get('hangyi_order', array('id' => $_GPC['id'], 'sell_id' => $userinfo['uid']));
    if ($orderinfo['id']) {
        $re = pdo_query("UPDATE " . tablename('hangyi_order') . " SET `order_status`='2',`tracking_number`='" . $_GPC['barCode'] . "',`send_time`='" . time() . "'  WHERE id = '" . $orderinfo['id'] . "' and `sell_id`='" . $userinfo['uid'] . "' limit 1");
        $account = $_W['account'];

        $orderinfo = pdo_get('hangyi_order', array('id' => $_GPC['id'], 'sell_id' => $userinfo['uid']));
        $orders = $orderinfo;
        $setting_fh = pdo_get('hangyi_tplset', array('tpl_type' => 2, 'uniacid' => $uniacid));
        load()->classs('weixin.account');
        $acc = new WeiXinAccount($account);
        if ($setting_fh['status']) {
            $data = array(
                'first' => array(
                    'value' => "亲，您在" . $orders['sell_nickname'] . " 那里购买的 " . $orders['goodsName'] . " 已经发货啦，订单号：" . $orderinfo['id'],
                    'color' => $setting_fh['title_color']
                ),
                'delivername' => array(
                    'value' => '点击查看',
                    'color' => $setting_fh['tpl_word_1_color']
                ),
                'ordername' => array(
                    'value' => $orders['tracking_number'],
                    'color' => $setting_fh['tpl_word_2_color']
                ),
                'remark' => array(
                    'value' => "点击这里查看快递物流跟踪信息>>>",
                    'color' => $setting_fh['tpl_word_4_color']
                ),
            );
        } else {

            $data = array(
                'first' => array(
                    'value' => "亲，您在" . $orders['sell_nickname'] . " 那里购买的 " . $orders['goodsName'] . " 已经发货啦，订单号：" . $orderinfo['id'],
                    'color' => '#ff510'
                ),
                'delivername' => array(
                    'value' => '点击查看',
                    'color' => '#ff510'
                ),
                'ordername' => array(
                    'value' => $orders['tracking_number'],
                    'color' => '#ff510'
                ),
                'remark' => array(
                    'value' => "点击这里查看快递物流跟踪信息>>>",
                    'color' => '#ff510'
                ),
            );
        }

        $acc->sendTplNotice($orders['buy_openid'], $setting_fh['template_id'], $data, 'http://m.kuaidi100.com/result.jsp?nu=' . $orders['tracking_number'], $topcolor = '#FF683F');

        if ($re) {
            echo 1;
        }
    }
    exit();
}

$sqlPre = 'SELECT u.nickname as nickname,o.order_status as order_status,p.goodsName as goodsName,o.id as id,p.goodsImg as goodsImg,o.buy_nick as buy_nick, o.cell as cell,o.address as address,o.pay_time as pay_time FROM ' . tablename('hangyi_order') . " as o left join " . tablename('hangyi_product') . " as  p on o.pid=p.id left join " . tablename('hangyi_user') . " as u on u.uid=o.buy_id WHERE ";

if ($_GPC['order_status']) {
    if ($search) {
        $condition = "o.sell_id ={$uid} AND o.uniacid={$_W['uniacid']} and o.`order_status`='" . $_GPC['order_status'] . "' and (p.goodsName like '%" . $search . "%' or u.nickname like '%" . $search . "%' or o.buy_nick like '%" . $search . "%')";
    } else {
        $condition = "o.sell_id = {$uid} AND o.uniacid={$_W['uniacid']} and o.`order_status`='" . $_GPC['order_status'] . "' ";
    }
   $condition .= " AND o.pay_status=2";
} else {
    if ($search) {
        $condition = "o.sell_id = {$uid} AND o.uniacid={$_W['uniacid']} and (p.goodsName like '%" . $search . "%' or u.nickname like '%" . $search . "%' or o.buy_nick like '%" . $search . "%')";
    } else {
        $condition = "o.sell_id = {$uid} AND o.uniacid={$_W['uniacid']}";
    }
}

//待付款
if ($_GPC['pay_status'] !== NULL) {
    $condition .= " AND o.pay_status={$_GPC['pay_status']}";
}

$order = " order by o.id desc";

$sql = $sqlPre . $condition . $order;

$trades_or = pdo_fetchall($sql);
include $this->template('orderslist');
?>




