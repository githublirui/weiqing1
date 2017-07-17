<?php

global $_W, $_GPC;
$do = (string) trim($_GPC['sdo']);
session_start();

/**
 * 执行动作
 */
require_once WXZ_PAYMENT . '/function/global.func.php';
$_W['module_setting'] = $this->module['config'];
$className = "ControllerAjaxWeb";
$classObj = new $className();

if (is_callable(array($classObj, $do))) {
    call_user_func(array($classObj, $do), $this);
} else {
    ajaxReturnFormat('', '404 not found');
}

class ControllerAjaxWeb extends WXZ_PAYMENTModuleSite {

    /**
     * 检查订单状态
     */
    public function check_order($module) {
        global $_W, $_GPC;

        require_once WXZ_PAYMENT . '/source/Order.class.php';
        require_once WXZ_PAYMENT . '/source/Fans.class.php';
        $orderId = (int) $_GPC['order_id'];
        if (!$orderId) {
            ajaxReturnFormat(0, '订单参数错误');
        }
        $orderInfo = Order::getOrderById($orderId, 'status,credit,fail_reason,pay_money,fans_id');
        $orderInfo['pay_money'] = sprintf("%.2f", $orderInfo['pay_money'] / 100);
        $orderInfo['username'] = '';
        $orderInfo['plate_number'] = '';
        if ($orderInfo && $orderInfo['fans_id']) {
            $fans = new Fans();
            $user = $fans->getOne($orderInfo['fans_id']);
            $orderInfo['username'] = $user['username'];
            $orderInfo['plate_number'] = $user['plate_number'];
        }
        ajaxReturnFormat(1, '', $orderInfo);
    }

    /**
     * 生成支付订单
     */
    public function do_pay() {
        global $_W, $_GPC;

        require_once WXZ_PAYMENT . '/source/Order.class.php';

        $money = floatval($_GPC['money']); //支付金额
        $money = number_format($money, 2);
        $money_fen = $money * 100;
        if ($money_fen < 1) {
            ajaxReturnFormat(0, "金额错误,{$money_fen}");
        }

        $orderData = array(
            'order_no' => Order::getOrderNo(),
            'status' => 1,
            'money' => $money_fen,
            'pay_money' => $money_fen, // 
        ); //创建订单信息

        $orderId = Order::creteOrder($orderData); //创建订单
        ajaxReturnFormat(1, '', array('order_id' => $orderId));
    }

}

?>
