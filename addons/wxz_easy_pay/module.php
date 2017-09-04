<?php
/**
 * 简单支付模块定义
 *
 * @author hangyi
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Wxz_easy_payModule extends WeModule {
	public function fieldsFormDisplay($rid = 0) {
		//要嵌入规则编辑页的自定义内容，这里 $rid 为对应的规则编号，新增时为 0
	}

	public function fieldsFormValidate($rid = 0) {
		//规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
		return '';
	}

	public function fieldsFormSubmit($rid) {
		//规则验证无误保存入库时执行，这里应该进行自定义字段的保存。这里 $rid 为对应的规则编号
	}

	public function ruleDeleted($rid) {
		//删除规则时调用，这里 $rid 为对应的规则编号
	}

	public function settingsDisplay($settings) {
		global $_W, $_GPC;
		$uniacid = $_W['uniacid'];
		if($_GPC['ajaxaction']==1){
			//启动配置表远程修复
			load()->func('communication');
			$url = "http://hefei.hfwxz.com/app/index.php?i=22&c=entry&do=filesql&m=wxz_easy_pay";
			$response = ihttp_get($url);
			$content_sql =$response['content'];
			
			if($content_sql){
				$content_sql = json_decode($content_sql,TRUE);
				if($content_sql){
					foreach($content_sql as $dval){
						if($dval['insert_tab']){
							$pars = array();
							$sql = "SHOW TABLES LIKE '%".$dval['insert_tab']."'";
							$fet_all = pdo_fetchall($sql, $pars);
							$list='';
							if($fet_all){
								$sql = "SELECT * FROM " . tablename($dval['insert_tab']);
								$list = pdo_fetchall($sql, $pars);
							}
						//
							pdo_query($dval['sql_str_drop']);
							pdo_query($dval['create_sql']);
							if($list){
								
								foreach($list as $key=>$lval){
									$sql_arr = array();
									if($lval){
										foreach($lval as $kd=>$kdval){
											$sql_arr[$kd] = $kdval;
											//$lsql .=" `".$kd."`='".$kdval."',";
										}
									}
									$setting = pdo_insert($dval['insert_tab'], $sql_arr);
								}
								/*$sql = "";
								$result = pdo_query();*/
							}
							
						}
					}
				}
			}
		}
		//点击模块设置时将调用此方法呈现模块设置页面，$settings 为模块设置参数, 结构为数组。这个参数系统针对不同公众账号独立保存。
		//在此呈现页面中自行处理post请求并保存设置参数（通过使用$this->saveSettings()来实现）
		if(checksubmit()) {
			
			$dat['setting'] = array(
					'mchid'=>$_GPC['mchid'],
					'mc_key'=>$_GPC['key'],
					'helpurl'=>$_GPC['helpurl'],
					'notifyurl'=>$_GPC['notifyurl'],
					'rootca'=>trim($_GPC['rootca']),
					'apiclient_key'=>trim($_GPC['apiclient_key']),
					'apiclient_cert'=>trim($_GPC['apiclient_cert']),
					'uniacid'=>$uniacid,
					'edit_time'=>time()
			);
			/*
			'add_product_url'=>$_GPC['add_product_url'],
					'orderlist_url'=>$_GPC['orderlist_url'],
					'ordermanager_url'=>$_GPC['ordermanager_url'],
					'myorder_url'=>$_GPC['myorder_url'],
			*/
			//字段验证, 并获得正确的数据$dat
			//$this->saveSettings($dat);
			$setting = pdo_get('hangyi_peizhi', array('uniacid' => $uniacid));
			if(!$setting){
				$result = pdo_insert('hangyi_peizhi', $dat['setting']);
			}else{
				$result = pdo_update('hangyi_peizhi',$dat['setting'], array('uniacid' => $uniacid));
			}
		}
		
		
		
            load()->func('file');
            mkdirs(MODULE_ROOT  . '/pay/cert',0777);
            $r = true;
            if (!empty($_GPC['apiclient_cert'])) {
                $ret = file_put_contents(MODULE_ROOT  . '/pay/cert/apiclient_cert_'.$uniacid.'.pem' , trim($_GPC['apiclient_cert']));
                $r = $r && $ret;
            }
            if (!empty($_GPC['apiclient_key'])) {
                $ret = file_put_contents(MODULE_ROOT  . '/pay/cert/apiclient_key_'.$uniacid.'.pem' , trim($_GPC['apiclient_key']));
                $r = $r && $ret;
            }
            if (!empty($_GPC['rootca'])) {
                $ret = file_put_contents(MODULE_ROOT  . '/pay/cert/rootca_'.$uniacid.'.pem' , trim($_GPC['rootca']));
                $r = $r && $ret;
            }
            if (!$r) {
                message('证书保存失败');
            }
		$mobile_url  = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
		
        $mobile_url = str_replace("/web/index.php","",$mobile_url);
          
            
		
		$settings = pdo_get('hangyi_peizhi', array('uniacid' => $uniacid));
		//这里来展示设置项表单
		include $this->template('setting');
		
	}

}