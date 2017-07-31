<?php
/**
 * 签到累积分模块微站定义
 *
 * @author unrooter
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Wm_qiandaoModuleSite extends WeModuleSite {

	public function doMobileEntry() {
		global $_W, $_GPC;
        $op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        if($op == 'display'){
			$today = date('Ymd',time());
			$month = date('Ym',time());
			$res = pdo_fetchcolumn("SELECT id FROM ".tablename('mc_card_sign_record')." WHERE uid = :uid AND date_format(FROM_UNIXTIME(addtime),'%Y%m%d') = {$today}",array(':uid'=>$_W['member']['uid']));
			$sumcredit = pdo_fetchcolumn("SELECT SUM(credit) AS sumcredit FROM ".tablename('mc_card_sign_record')." WHERE uid = :uid AND date_format(FROM_UNIXTIME(addtime),'%Y%m') = {$month}",array(':uid'=>$_W['member']['uid']));
			$lists = pdo_fetchall("SELECT id,date_format(FROM_UNIXTIME(addtime),'%d') as signday FROM ".tablename('mc_card_sign_record')." WHERE uid = :uid AND date_format(FROM_UNIXTIME(addtime),'%Y%m') = {$month}",array(':uid'=>$_W['member']['uid']));
			foreach ($lists as $key => $value) {
				if(substr($value['signday'], 0,1) == '0'){
					$ids .=(intval(substr($value['signday'], 1))-1).',';
				}
			}
			$ids = substr($ids, 0,-1);
        }
		if($op =='sign' ){
			$settings = $this->module['config'];
			//是否签到
			$today = date('Ymd',time());
			$res = pdo_fetchcolumn("SELECT id FROM ".tablename('mc_card_sign_record')." WHERE uid = :uid AND date_format(FROM_UNIXTIME(addtime),'%Y%m%d') = '{$today}'",array(':uid'=>$_W['member']['uid']));
			if(empty($res)){
				$insertData['uid'] = $_W['member']['uid'];
				$insertData['uniacid'] = $_W['uniacid'];
				$insertData['credit'] = $settings['score'];
				$insertData['addtime'] = time();
				$res2 = pdo_insert('mc_card_sign_record',$insertData);
				if(!empty($res2)){
					//添加积分
					pdo_query("UPDATE ".tablename('mc_members')." SET {$settings['field']} = {$settings['field']} + '{$settings['score']}' WHERE uid = :uid", array(':uid' => $_W['member']['uid']));
					exit(json_encode(array(
						'status' => true,
						'msg'    =>'签到成功'
			        )));
				}
			}else{
				exit(json_encode(array(
					'status' => false,
					'msg'    =>'已签到'
		        )));
			}
			//积分奖励
		}
		include $this->template('entry');
	}
	public function doWebRecords() {
		global $_W, $_GPC;
		$contents = pdo_fetchall("SELECT a.*,b.nickname FROM ".tablename('mc_card_sign_record')." a LEFT JOIN ".tablename('mc_members')." b ON a.uid = b.uid WHERE a.uniacid = :uniacid",array(':uniacid'=>$_W['uniacid']));
		include $this->template('records');
	}

}