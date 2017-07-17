<?php

global $_W, $_GPC;
$_W['module_setting'] = $this->module['config'];

ini_set('date.timezone', 'Asia/Shanghai');
error_reporting(E_ERROR);

require_once WXZ_PAYMENT . "/lib/wxpay/lib/WxPay.Api.php";
require_once WXZ_PAYMENT . "/lib/wxpay/lib/WxPay.Notify.php";

class PayNotifyCallBack extends WxPayNotify {

    //查询订单
    public function Queryorder($transaction_id) {
        $input = new WxPayOrderQuery();
        $input->SetTransaction_id($transaction_id);
        $result = WxPayApi::orderQuery($input);
        if (array_key_exists("return_code", $result) && array_key_exists("result_code", $result) && $result["return_code"] == "SUCCESS" && $result["result_code"] == "SUCCESS") {
            return true;
        }
        return false;
    }

    //重写回调处理函数
    public function NotifyProcess($data, &$msg) {
        global $_W;
        $notfiyOutput = array();
        if (!array_key_exists("transaction_id", $data)) {
            $msg = "输入参数不正确";
            return false;
        }
        //查询订单，判断订单真实性
        if (!$this->Queryorder($data["transaction_id"])) {
            $msg = "订单查询失败";
            return false;
        }
        require_once WXZ_PAYMENT . '/source/Order.class.php';
        require_once WXZ_PAYMENT . '/source/Fans.class.php';
        $orderInfo = Order::getOrderByOrderNo($data['out_trade_no']);
        if (!$orderInfo) {
            $msg = "订单不存在";
            return false;
        }
        if ($orderInfo['status'] != 1) {
            $msg = "订单已处理";
            return false;
        }

        if ($data['result_code'] != 'SUCCESS' || $data['return_code'] != 'SUCCESS') {
            $update = array(
                'status' => 3,
                'fail_reason' => $data['fail_reason'],
                'update_at' => time(),
            );
            Order::updateById($orderInfo['id'], $update);
            $msg = "失败订单";
            return false;
        }
        $update = array(
            'status' => 2,
            'trade_no' => $data['out_trade_no'],
            'update_at' => time(),
            'success_at' => time(),
        );
        Order::updateById($orderInfo['id'], $update);
        Order::doSuccess($orderInfo);
        $_W['account'] = uni_fetch($_W['uniacid']);
      
        //发送模版消息
        $fans = new Fans();
        $account_obj = WeAccount::create($_W['account']);
        $wxData = array(
            'remark' => array(
                'value' => ''
            ),
        );
        $payMoney = number_format($orderInfo['pay_money'] / 100, 2);
        $wxData['first']['value'] = "用户付款成功";
        $wxData['keyword1']['value'] = $orderInfo['order_no'];
        $wxData['keyword2']['value'] = $_W['module_setting']['merchant_name'];
        $wxData['keyword3']['value'] = "{$payMoney}元";
        $admins = $fans->getAllAdmin();
        foreach ($admins as $admin) {
           $account_obj->sendTplNotice($admin['openid'], 'YfQWKrVZy14qAArYX1w_s7-nlYDxL_szG7gWk39NrkA', $wxData, '');
        }
        return true;
    }

}

$notify = new PayNotifyCallBack();
$notify->Handle(false);
