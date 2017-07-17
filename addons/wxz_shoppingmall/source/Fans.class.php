<?php

class Fans {

    //地区选择
    public static $addresses = array(
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

    /**
     * 会员等级
     * @var type 
     */
    public static $levels = array(
        1 => '普通',
        2 => '中级',
        3 => '高级',
    );

    public static function getLevels() {
        global $_W;
        return explode(',', $_W['module_setting']['fans_levels']);
    }

    /**
     * 保存一条用户记录至用户表中, 如果OpenID存在, 则更新记录
     * @param array $entity     用户数据
     * @return int|error        成功返回用户编号, 失败返回错误信息
     */
    public function save($entity) {
        global $_W;
        $rec = array_elements(array('openid', 'nickname', 'gender', 'state', 'city', 'country', 'avatar', 'client_ip', 'create_at'), $entity);
        $rec['uniacid'] = $_W['uniacid'];
        $sql = 'SELECT * FROM ' . tablename('wxz_shoppingmall_fans') . ' WHERE `uniacid`=:uniacid AND `openid`=:openid';
        $pars = array();
        $pars[':uniacid'] = $rec['uniacid'];
        $pars[':openid'] = $rec['openid'];
        $exists = pdo_fetch($sql, $pars);
        if (!empty($exists)) {
            $filter = array();
            $filter['uniacid'] = $_W['uniacid'];
            $filter['uid'] = $exists['uid'];
            $ret = pdo_update('wxz_shoppingmall_fans', $rec, $filter);
            if ($ret !== false) {
                return $exists['uid'];
            } else {
                return error(-2, '数据更新失败, 请稍后重试');
            }
        }
        $ret = pdo_insert('wxz_shoppingmall_fans', $rec);
        if (!empty($ret)) {
            return pdo_insertid();
        } else {
            return error(-1, '数据保存失败, 请稍后重试');
        }
    }

    public function remove($uid, $isOpenid = false) {
        global $_W;
        $pars = array();
        $pars['uniacid'] = $_W['uniacid'];
        if ($isOpenid) {
            $pars['openid'] = $uid;
        } else {
            $pars['uid'] = intval($uid);
        }
        $del = pdo_delete('wxz_shoppingmall_fans', $pars);
        if ($del !== false) {
            return true;
        } else {
            return error(-1, '数据删除失败, 请稍后重试');
        }
    }

    public function modify($uid, $entity, $isOpenid = false) {
        global $_W;
        $rec = array_elements(array('unionid', 'nickname', 'gender', 'state', 'city', 'country', 'avatar'), $entity);
        $rec['uniacid'] = $_W['uniacid'];
        $filter = array();
        if ($isOpenid) {
            $filter['openid'] = $uid;
        } else {
            $filter['uid'] = intval($uid);
        }
        $ret = pdo_update('wxz_shoppingmall_fans', $rec, $filter);
        if ($ret !== false) {
            return true;
        } else {
            return error(-1, '数据更新失败, 请稍后重试');
        }
    }

    public function getOne($uid, $isOpenid = false) {
        global $_W;
        $pars = array();
        $pars[':uniacid'] = $_W['uniacid'];
        if ($isOpenid) {
            $pars[':openid'] = $uid;
            $sql = 'SELECT * FROM ' . tablename('wxz_shoppingmall_fans') . ' WHERE `uniacid`=:uniacid AND `openid` =:openid';
        } else {
            $pars[':uid'] = intval($uid);
            $sql = 'SELECT * FROM ' . tablename('wxz_shoppingmall_fans') . ' WHERE `uniacid`=:uniacid AND `uid` =:uid';
        }

        $ret = pdo_fetch($sql, $pars);
        if (!empty($ret)) {
            return $ret;
        } else {
            return array();
        }
    }

    public function getAll($filters = array(), $pindex = 0, $psize = 15, &$total = 0) {
        global $_W;
        $condition = '`f`.`uniacid`=:uniacid';
        $pars = array();
        $pars[':uniacid'] = $_W['uniacid'];
        if (!empty($filters['nickname'])) {
            $condition .= ' AND  `f`.`nickname` LIKE :nickname';
            $pars[':nickname'] = "%{$filters['nickname']}%";
        }
        if (!empty($filters['status'])) {
            if ($filters['status'] == 'success') {
                $condition .= " AND `r`.`status`='success'";
            } else {
                $condition .= " AND (`r`.`status`!='success' OR `r`.`status` IS NULL)";
            }
        }
        $sql = 'FROM ' . tablename('wxz_shoppingmall_fans') . ' AS `f` LEFT JOIN ' . tablename('mbrp_records') . ' AS `r` ON (`f`.`uid`=`r`.`uid`) WHERE ';
        $sql .= $condition;
        if ($pindex > 0) {
            $total = pdo_fetchcolumn("SELECT COUNT(*) {$sql}", $pars);
            $start = ($pindex - 1) * $psize;
            $sql .= " ORDER BY `f`.`uid` DESC LIMIT {$start},{$psize}";
            $ds = pdo_fetchall("SELECT `f`.*,`r`.`status`,`r`.`id`,`r`.`type`,`r`.`fee`,`r`.`status` AS `send` {$sql}", $pars);
        } else {
            $sql .= " ORDER BY `f`.`uid` DESC";
            $ds = pdo_fetchall("SELECT `f`.*,`r`.`id`,`r`.`type`,`r`.`fee`,`r`.`status` AS `send` {$sql}", $pars);
        }
        if (!empty($ds)) {
            foreach ($ds as &$row) {
                $row['helps'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mbrp_helps') . ' WHERE `uniacid`=:uniacid AND `from`=:uid', array(':uniacid' => $_W['uniacid'], ':uid' => $row['uid']));
            }
            unset($row);
        }
        return $ds;
    }

    public function update_by_id($id, $entity) {
        $ret = pdo_update('wxz_shoppingmall_fans', $entity, array('uid' => $id));
        if ($ret) {
            return true;
        }
        return false;
    }

    /**
     * 通过手机号获取用户
     * @param type $mobile
     */
    public function getByMobile($mobile) {
        global $_W;
        if ($mobile) {
            $sql = "SELECT * FROM " . tablename('wxz_shoppingmall_fans') . " WHERE `uniacid`={$_W['uniacid']} AND `mobile` ='{$mobile}'";
            $ret = pdo_fetch($sql);
        }

        if (!empty($ret)) {
            return $ret;
        } else {
            return array();
        }
    }

    /**
     * 用户积分操作
     * @param type $uid
     * @param type $credit
     * @param type $operate 1添加 2减去
     */
    public function updateCredit($uid, $credit, $operate) {
        global $_W;
        if ($operate == 1) {
            $update_fans = "UPDATE  " . tablename('wxz_shoppingmall_fans') . " set credit=credit+{$credit},left_credit=left_credit+{$credit} where uid='{$uid}' AND `uniacid`={$_W['uniacid']}"; //添加积分
        } else if ($operate == 2) {
            $update_fans = "UPDATE  " . tablename('wxz_shoppingmall_fans') . " set left_credit=left_credit-{$credit},use_credit=use_credit+{$credit} where uid='{$uid}'"; //减少积分
        }
        return pdo_query($update_fans);
    }

    /**
     * 用户余额操作
     * @param type $uid
     * @param type $money
     * @param type $operate 1添加 2减去
     */
    public function updateAccount($uid, $money, $operate) {
        global $_W;
        if ($operate == 1) {
            $update_fans = "UPDATE  " . tablename('wxz_shoppingmall_fans') . " set account=account+{$money} where uid='{$uid}' AND `uniacid`={$_W['uniacid']}"; //添加积分
        } else if ($operate == 2) {
            $update_fans = "UPDATE  " . tablename('wxz_shoppingmall_fans') . " set account=account-{$money} where uid='{$uid}' AND `uniacid`={$_W['uniacid']}"; //减少积分
        }
        return pdo_query($update_fans);
    }

}
