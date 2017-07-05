<?php

global $_W, $_GPC;
require_once WXZ_SHOPPINGMALL . '/lib/phpqrcode/phpqrcode.php';
$cacheKey = "wxz_shoppingmall_qrcode_num_{$_W['uniacid']}";
set_time_limit(-1);
$cache = cache_read($cacheKey);

function generateRandomString($length = 10) {
    $characters1 = '0123456789';
    $characters2 = 'abcdefghijklmnopqrstuvwxyz';
    $charactersLength1 = strlen($characters1);
    $charactersLength2 = strlen($characters2);
    $randomString = '';
    $length1 = rand(1, 4);
    $length2 = rand(3, 5);
    $length3 = 10 - $length1 - $length2;
    for ($i = 0; $i < $length1; $i++) {
        $randomString .= $characters1[rand(0, $charactersLength1 - 1)];
    }
    for ($i = 0; $i < $length2; $i++) {
        $randomString .= $characters2[rand(0, $charactersLength2 - 1)];
    }
    for ($i = 0; $i < $length3; $i++) {
        $randomString .= $characters1[rand(0, $charactersLength1 - 1)];
    }
    return $randomString;
}

//$sql = "select count(1) from ims_wxz_shoppingmall_invite_code group by code having count(code)=1";
//$ret = pdo_fetch($sql);
//$sql = "select id,code from ims_wxz_shoppingmall_invite_code";
//$ret = pdo_fetchall($sql);
//foreach ($ret as $row) {
//    echo $row['id'] . ',' . $randNum = sprintf('%08d', $row['code']) . ',' . date('Ymd') . '_' . $row['code'] . '.png' . "\n";
//}
//die;
if ($cache) {
    $cacheData = $cache;
    if ($cacheData['num'] > 0) {
        $runNum = $cacheData['num'] >= 100 ? 100 : $cacheData['num'];
        for ($i = 0; $i < $runNum; $i++) {
            //生成随机邀请码
            for ($try = 0; $try < 10; $try++) {
                $randNum = generateRandomString(10);
                $existSql = "SELECT id FROM " . tablename('wxz_shoppingmall_invite_code') . " WHERE code='{$randNum}'";
                $exist = pdo_fetchcolumn($existSql);
                if (!$exist) {
                    break;
                }
            }
            //生成二维码
//            $url = "{$_W['siteroot']}app/index.php?i={$_W['uniacid']}&c=entry&do=reg&m={$_GPC['m']}&code={$randNum}";
//            $savePath = $cacheData['path'] . "/" . date('Ymd') . '_' . $randNum . '.png';
//            for ($try = 0; $try < 10; $try++) {
//                $ret = QRcode::png($url, $savePath, QR_ECLEVEL_Q, 4);
//                if (file_exists($savePath)) {
//                    break;
//                }
//            }
            $data = array(
                'uniacid' => $_W['uniacid'],
                'code' => $randNum,
                'create_at' => time(),
            );
//            if (file_exists($savePath)) {
            pdo_insert('wxz_shoppingmall_invite_code', $data);
//            }
        }
        $cacheData['num'] = $cacheData['num'] - $runNum;
        if ($cacheData['num'] <= 0) {
            $succ = "生成成功，共生成成功{$cacheData['total']}个邀请二维码";
            cache_delete($cacheKey);
            $cacheData = array();
        } else {
            cache_write($cacheKey, $cacheData);
            $succ = "生成成功{$runNum}个邀请二维码";
        }
    }
}

if (checksubmit()) {
    $num = (int) $_GPC['num'];
    $path = $_GPC['path'];
    $cacheData = array('num' => $num, 'total' => $num, 'path' => $path);
    cache_write($cacheKey, $cacheData);
    header('Location: ' . $this->createWebUrl('gen_reg_qrcode'));
    exit;
//    for ($i = 0; $i < $num; $i++) {
//        //生成随机邀请码
//        $randNum = mt_rand(1, 99999999);
//        $randNum = sprintf('%08d', $randNum);
//        //生成二维码
//        $url = "{$_W['siteroot']}app/index.php?i={$_W['uniacid']}&c=entry&do=reg&m={$_GPC['m']}&code={$randNum}";
//        $savePath = $path . "/" . date('Ymd') . '_' . $randNum . '.png';
//        QRcode::png($url, $savePath, QR_ECLEVEL_Q, 4);
//        $data = array(
//            'uniacid' => $_W['uniacid'],
//            'code' => $randNum,
//            'create_at' => time(),
//        );
//        pdo_insert('wxz_shoppingmall_invite_code', $data);
//    }
//    $succ = "已生成成功{$num}个邀请二维码,二维码目录$path";
}
include $this->template('web/gen_reg_qrcode');
?>
