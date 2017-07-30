<?php
/**
 * 签到累积分模块定义
 *
 * @author unrooter
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Wm_qiandaoModule extends WeModule {

	public function settingsDisplay($settings) {
		global $_W, $_GPC;
		if(checksubmit()) {
			$cfg = $settings;
			$cfg['score'] = trim($_GPC['score']);
			$cfg['field'] = trim($_GPC['field']);
			if ($this->saveSettings($cfg)) {
			    message('保存成功', 'refresh');
			}
		}
		include $this->template('setting');
	}

}