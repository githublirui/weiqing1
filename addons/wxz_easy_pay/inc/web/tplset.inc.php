<?php
global $_W,$_GPC;
$uniacid = $_W['uniacid'];


if ($_GPC['postform']) {
			$dat['setting'] = array(
					'title'=>$_GPC['firstvalue'],
					'title_color'=>$_GPC['firstcolor'],
					'template_id'=>$_GPC['template_id'],
					'tpl_word_1'=>$_GPC['keyword1value'],
					'tpl_word_1_color'=>$_GPC['keyword1color'],
					'tpl_word_2'=>$_GPC['keyword2value'],
					'tpl_word_2_color'=>trim($_GPC['keyword2color']),
					'tpl_word_3'=>trim($_GPC['keyword3value']),
					'tpl_word_3_color'=>trim($_GPC['keyword3color']),
					'tpl_word_4'=>trim($_GPC['remarkvalue']),
					'tpl_word_4_color'=>trim($_GPC['remarkcolor']),
					'uniacid'=>$uniacid,
					'tpl_type'=>intval($_GPC['tpl_type']),
					'status'=>intval($_GPC['status']),
					'edit_time'=>time()
			);
			
			$setting = pdo_get('hangyi_tplset', array('tpl_type' => intval($_GPC['tpl_type']),'uniacid'=>$uniacid));
			if(!$setting){
				$result = pdo_insert('hangyi_tplset', $dat['setting']);
			}else{
				$result = pdo_update('hangyi_tplset',$dat['setting'], array('tpl_type' => intval($_GPC['tpl_type']),'uniacid'=>$uniacid));
			}
		}
		

$setting_zhifu = pdo_get('hangyi_tplset', array('tpl_type' => 1,'uniacid'=>$uniacid));
$setting_fh = pdo_get('hangyi_tplset', array('tpl_type' => 2,'uniacid'=>$uniacid));
include $this->template('tpl_list');