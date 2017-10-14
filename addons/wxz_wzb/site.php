<?php
/**
 * 小智-微直播（传播版）模块微站定义
 *
 * @author wxz
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');
define('TIMESTAMP',time());
define('PAYTYPE',1);
class Wxz_wzbModuleSite extends WeModuleSite {

	public function doMobileInterface(){
		require IA_ROOT . '/addons/wxz_wzb/tis/interface.php';
	}
	public function doWebInterface(){
		require IA_ROOT . '/addons/wxz_wzb/tis/interface.php';
	}
	public function doMobileIndex(){
		global $_W,$_GPC;
		
		$sub_openid = $_GPC['sub_openid'];
		$share_uid = $_GPC['share_uid'];
		
		$uid = $this->authss();

		$user = pdo_fetch('SELECT * FROM ' . tablename('wxz_wzb_user') . ' WHERE `uniacid` = :uniacid AND `id` = :uid', array(':uniacid' => $_W['uniacid'],':uid' => $uid) );

		if (!$user['sub_openid']){
			if($sub_openid ){
				$data=array(
					'sub_openid'=>$sub_openid
				);
				pdo_update('wxz_wzb_user', $data, array('id' => $uid));
			}
		}
		$wlive= pdo_fetchall("SELECT * FROM ".tablename('wxz_wzb_wlive_setting'));

		if(!empty($wlive)){
			foreach($wlive as $key => $v){
				$data['sort'] = $v['sort'];
				$data['title'] = $v['title'];
				$data['total_num'] = $v['total_num'];
				$data['real_num'] = $v['real_num'];
				$data['islinkurl'] = $v['islinkurl'];
				$data['linkurl'] = $v['linkurl'];
				$data['isshow'] = $v['isshow'];
				$data['cid'] = $v['cid'];
				$data['dateline'] = $v['dateline'];
				$data['uniacid'] = $v['uniacid'];
				$data['img'] = $v['img'];
				pdo_insert('wxz_wzb_live_setting', $data);
				pdo_delete('wxz_wzb_wlive_setting',array('id'=>$v['id']));
			}
		}
		$banner = pdo_fetchAll('SELECT * FROM ' . tablename('wxz_wzb_banner').' where `uniacid` = '.$_W['uniacid'].'  AND isshow=1  order by sort desc');
		$lists = array();
		$list = pdo_fetchall("SELECT * FROM ".tablename('wxz_wzb_category')." WHERE uniacid=:uniacid AND isshow=:isshow  ORDER BY sort ASC,dateline DESC",array(':uniacid'=>$_W['uniacid'],':isshow'=>'1'));
		if(!empty($list) && is_array($list)){
			foreach($list as $key=>$row){
					$row['list'] = pdo_fetchall("SELECT * FROM ".tablename('wxz_wzb_live_setting')." WHERE uniacid=:uniacid AND cid=:cid AND isshow=:isshow ORDER BY sort ASC,dateline DESC LIMIT 8",array(":uniacid"=>$_W['uniacid'],':cid'=>$row['id'],':isshow'=>'1'));
					
					$wlive['list'] = pdo_fetchall("SELECT * FROM ".tablename('wxz_wzb_wlive_setting')." WHERE uniacid=:uniacid and cid=:cid AND isshow=1 ORDER BY sort ASC,dateline DESC",array(':uniacid'=>$_W['uniacid'],':cid'=>$row['id']));

					if(empty($row['list']) && empty($wlive['list'])){
						unset($list[$key]);
					}else{
						$row['list'] = array_merge($row['list'],$wlive['list']);
						$lists[] = $row;
					}

			}
		}

		/**/
		$l = pdo_fetchall("SELECT * FROM ".tablename('wxz_wzb_live_setting'));
		if(!empty($l) && is_array($l)){
			foreach($l as $key=>$row){
				if($row['cid']>0){
					$r = pdo_fetch("SELECT * FROM ".tablename('wxz_wzb_category')." WHERE id=:id",array(':id'=>$row['cid']));
					if(empty($r)){
						pdo_delete('wxz_wzb_live_setting',array('id'=>$row['id']));
					}
				}
			}
		}
		/**/
		$setting = pdo_fetch('SELECT * FROM ' . tablename('wxz_wzb_list') . ' WHERE `uniacid` = :uniacid', array(':uniacid' => $_W['uniacid']));

		if($setting['style']==1){
			include $this->template('index');exit;
		}else{
			include $this->template('2/index');exit;
		}
	}

	public function getRedP($rid){
		global $_W,$_GPC;
		if($rid){
			$sql = "select a.id as id,a.min as min,a.max as max,a.uniacid as uniacid,a.type as type,a.reward_amount_min as reward_amount_min,a.reward_amount_max as reward_amount_max,a.pool_amount as pool_amount,a.send_amount as send_amount,a.packet_rule as packet_rule,a.rid as rid,a.withdraw_min as withdraw_min,b.mchid as mchid,b.password as password,b.appid as appid,b.secret as secret,b.ip as ip,b.sname as sname,b.wishing as wishing,b.actname as actname,b.logo as logo,b.apiclient_cert as apiclient_cert,b.apiclient_key as apiclient_key,b.rootca as rootca from ".tablename('wxz_wzb_live_red_packet')." as a inner join ".tablename('wxz_wzb_red_packet')." as b on a.uniacid = b.uniacid where a.uniacid =".$_W['uniacid']." AND a.rid =".$rid;
		}else{
			$sql = "select * from ".tablename('wxz_wzb_red_packet')."  where uniacid =".$_W['uniacid'];
		}
		
		$result = pdo_fetch($sql);
		return $result;
	}

	public function doWebRecommend(){
		global $_W,$_GPC;
		
		$id= $_GPC['id'];
		$rid= $_GPC['rid'];
		$single = pdo_fetch("select * from ".tablename('wxz_wzb_live_setting')." where id = ".$id);
		$recommend= $_GPC['recommend'];
		pdo_update('wxz_wzb_live_setting',array('recommend'=>$recommend),array('id' => $single['id']));
		$return_url = $_W['siteroot'] .'web'.str_replace("./","/",$this->createWebUrl('liveList',array('rid'=>$rid)));
        header("location: $return_url");
        exit;
	}

	public function doMobileClist(){
		global $_W,$_GPC;
		
		$cid = intval($_GPC['cid']);
		if(empty($cid)){
			message('分类错误！',$this->createMobileUrl('index'),'error');
		}
		$category = pdo_fetch("SELECT `title`,`id` FROM ".tablename('wxz_wzb_category')." WHERE uniacid=:uniacid AND id=:id",array(':uniacid'=>$_W['uniacid'],':id'=>$cid));
		$lists = pdo_fetchall("SELECT * FROM ".tablename('wxz_wzb_live_setting')." WHERE uniacid=:uniacid AND cid=:cid AND isshow=:isshow ORDER BY sort ASC,dateline DESC",array(':uniacid'=>$_W['uniacid'],':cid'=>$cid,':isshow'=>'1'));
		$setting = pdo_fetch('SELECT * FROM ' . tablename('wxz_wzb_list') . ' WHERE `uniacid` = :uniacid', array(':uniacid' => $_W['uniacid']));
		include $this->template('clist');
	}

	public function doMobileIndex2() {
		global $_W,$_GPC;
		$sub_openid = $_GPC['sub_openid'];
		$rid = $_GPC['rid'];
		$time = $_GPC['time'];
		$share_uid = $_GPC['share_uid'];
		
		$uid=$_GPC['wxz_wzb_user'.$_W['uniacid']];
		require IA_ROOT . '/addons/wxz_wzb/emo.php';

		$item = pdo_fetch('SELECT * FROM ' . tablename('wxz_wzb_live_setting') . ' WHERE `uniacid` = :uniacid and `rid` = :rid', array(':uniacid' => $_W['uniacid'],':rid' => $rid));
		if(empty($item)){
			message('直播间不存在！');
		}
		$uid = $this->auths($sub_openid,$item);
		$ditu = 0;
		$packet = $this->getRedP($rid);
		$zanlist = pdo_fetchAll('SELECT * FROM ' . tablename('wxz_wzb_zanpic') . ' WHERE `uniacid` = :uniacid and `rid` = :rid and isshow=1', array(':uniacid' => $_W['uniacid'],':rid' => $rid));
		$pic = array();
		foreach($zanlist as $key => $v){
			$pic[] = $v['pic'];
		}
		$pics = json_encode($pic);
		
		if($uid >0 ){
			$user = pdo_fetch('SELECT * FROM ' . tablename('wxz_wzb_user') . ' WHERE `uniacid` = :uniacid AND `id` = :uid', array(':uniacid' => $_W['uniacid'],':uid' => $uid) );
			
			$this->intoroom($rid,$user);
			
			$viewer = pdo_fetch('SELECT * FROM ' . tablename('wxz_wzb_viewer') . ' WHERE `uid` = :uid AND `rid` = :rid', array(':uid' => $uid,':rid' => $rid) );
			
			if (!$user['sub_openid']){
				if($sub_openid ){
					$data=array(
						'sub_openid'=>$sub_openid
					);
					pdo_update('wxz_wzb_user', $data, array('id' => $uid));
				}
			}

			if($share_uid && $uid && $share_uid != $uid){
			
				$this->addHelp($share_uid,$rid);
			}

			if($share_uid && $item['reward']==1 && $packet['type']==2 && $uid && $share_uid!=$uid){
				
				$this->addAmount($share_uid,$rid);
			}

			$paylog= pdo_fetch('SELECT * FROM ' . tablename('wxz_wzb_paylog') . ' WHERE `uniacid` = :uniacid AND `uid` = :uid AND `rid` = :rid AND type =:type', array(':uniacid' => $_W['uniacid'],':uid' => $uid,':rid' => $rid,':type' => 1) );

			if(!$paylog){
				$paylog['rid'] = $rid;
				$paylog['amount'] = $item['amount'];
				$paylog['uid'] = $uid;
				$paylog['uniacid'] = $_W['uniacid'];
				$paylog['lid'] = $item['id'];
				$paylog['type'] = 1;
				$paylog['status'] = 0;
				$paylog['dateline'] = time();
				$paylog['intotime'] = time();
				pdo_insert('wxz_wzb_paylog', $paylog);

			}
			if($item['limit'] == 3){
				$limit_time = $paylog['intotime'] + $item['delayed'];
			}

			if(($item['limit'] == 1 && $item['password'] != $viewer['password']) || ($item['limit'] == 2 && $paylog['status']!=1) || ($item['limit'] == 3 && $paylog['status']!=1 && ($limit_time - time())<0)){
				//include $this->template('verify');exit;
			}

			$sql='SELECT b.nickname,b.id,b.headimgurl,a.amount FROM ' . tablename('wxz_wzb_share') . ' as a inner JOIN ' . tablename('wxz_wzb_user') . ' AS b ON a.help_uid=b.id inner join ' . tablename('wxz_wzb_viewer') . ' as c on b.id=c.uid and a.rid=c.rid WHERE a.uniacid = '.$_W['uniacid'].' and a.share_uid='.$uid.' and c.rid='.$rid.' and a.amount>0 order by a.id desc';
			$help_user = pdo_fetchall($sql);

		}else{
			$help_user = array();
		}
		$LivePic = pdo_fetchAll('SELECT * FROM ' . tablename('wxz_wzb_live_pic').' where rid = '.$rid.' order by id desc');

		$list = pdo_fetch("SELECT * FROM ".tablename('wxz_wzb_live_video_type')." WHERE uniacid=:uniacid AND rid=:rid",array(':uniacid'=>$_W['uniacid'],':rid'=>$rid));
		
		if(!$list['player_height'] || !$list['player_weight'] ){
			$list['player_height'] = '720';
			$list['player_weight'] = '1280';
		}
		if($list){
			$list['settings'] = iunserializer($list['settings']);
		}
		if($list['type']==8){
			load()->func('communication');

			$response = ihttp_request('http://shuidi.huajiao.com/pc/view.html?sn='.$list['settings']['xsdroomid']);
			$url = 'https://live3.jia.360.cn/public/getInfoAndPlayV2?sn='.$list['settings']['xsdroomid'];  
			
			$response = ihttp_request($url); 
			$roominfo = json_decode($response['content']);
			if(!$roominfo->playInfo->hls){
				$list['settings']['hls'] = '';
				$list['settings']['rtmp'] = '';
				$list['settings']['img'] = $roominfo->publicInfo->thumbnail;
			}else{
				$list['settings']['hls'] = $roominfo->playInfo->hls;
				$list['settings']['rtmp'] = $roominfo->playInfo->rtmp;
				$list['settings']['img'] = $roominfo->publicInfo->thumbnail;
			}
		}
		if($list['type']==9){
			load()->func('communication');
			load()->func('communication');
			$loginurl = 'http://interface.yy.com/hls/get/0/'.$list['settings']['sid'].'/'.$list['settings']['ssid'].'?appid=0&excid=1200&type=m3u8&isHttps=0&callback=jsonp2';
			$response = ihttp_request($loginurl, array(), array(
				'CURLOPT_REFERER' => 'http://wap.yy.com/mobileweb/'.$list['settings']['sid'].'/'.$list['settings']['ssid'].'?tempId='.$list['settings']['tpl'].''
			));
			$result = json_decode(substr($response['content'],7,-1), true);
			if($result['code']==0){
				$list['settings']['hls'] = $result['hls'];
			}
		}
		$dashangsetting = pdo_fetch('SELECT * FROM ' . tablename('wxz_wzb_ds_setting').' where rid = '.$rid);
		if($item['style']==1){
			$Comments = pdo_fetchAll('SELECT * FROM ' . tablename('wxz_wzb_comment') .' where is_auth = 1 and rid = '.$rid.' order by id desc');
			$Comment = array_reduce($Comments, create_function('$v,$Comments', '$v[$Comments["id"]]=$Comments;return $v;'));  
			$packetuid = array();
			if(!empty($Comment)){
				foreach($Comment as $key => $v){
					if($uid == $v['uid'] && $v['toid']!=0 && !in_array($v['toid'],$packetuid)){
						$packetuid[] = $v['toid'];
					}
					if(isset($v['toid']) && $v['toid']>0){
						if(isset($Comment[$v['toid']])){
							
							$Comment[$v['toid']]['reply'][] = $v;
						}
						unset($Comment[$key]);
					}
					if($v['dsid'] && $v['dsstatus']==0){
						unset($Comment[$key]);
					}
				}
			}

			$spread = pdo_fetch('SELECT * FROM ' . tablename('wxz_wzb_spread_adv') . ' WHERE `uniacid` = :uniacid and `rid` = :rid and `type` = :type', array(':uniacid' => $_W['uniacid'],':rid' => $rid,':type' => 1));

			$roll = pdo_fetch('SELECT * FROM ' . tablename('wxz_wzb_roll_adv') . ' WHERE `uniacid` = :uniacid and `rid` = :rid and `type` = :type', array(':uniacid' => $_W['uniacid'],':rid' => $rid,':type' => 1));

			
			$setting = pdo_fetch('SELECT * FROM ' . tablename('wxz_wzb_setting') . ' WHERE `uniacid` = :uniacid and `rid` = :rid', array(':uniacid' => $_W['uniacid'],':rid' => $rid));

			if($spread['type']==1 && $time==0){
				include $this->template('spread');exit;
			}

			$sql ='SELECT b.nickname,b.headimgurl,count(*) as c FROM '.tablename('wxz_wzb_help').' as a inner join '.tablename('wxz_wzb_user').' as b on a.share_uid = b.id  inner join ' . tablename('wxz_wzb_viewer') . ' as c on b.id=c.uid and a.rid=c.rid where a.help_uid!=0 and a.rid='.$rid.' and  a.uniacid = '.$_W['uniacid'].' group by a.share_uid order by c desc';
			$help_user_count = pdo_fetchall($sql);

			$sql='SELECT b.nickname,b.id,b.headimgurl,a.amount FROM ' . tablename('wxz_wzb_viewer') . ' as a inner JOIN ' . tablename('wxz_wzb_user') . ' AS b ON a.uid=b.id WHERE a.rid='.$rid.' and a.amount>0 order by a.amount desc';
			$user_amount = pdo_fetchall($sql);


			$sql='SELECT b.nickname,b.id,b.headimgurl,sum(a.fee) as fee FROM ' . tablename('wxz_wzb_ds') . ' as a inner JOIN ' . tablename('wxz_wzb_user') . ' AS b ON a.uid=b.id WHERE a.rid='.$rid.' and a.fee>0 and a.status=1 group by uid ORDER BY fee DESC';
			$dsrank = pdo_fetchall($sql);

			$total_num = $this->num($item);

			
			$gift = pdo_fetchAll('SELECT * FROM ' . tablename('wxz_wzb_gift').' where rid = '.$rid.' and isshow=1 order by sort asc');

			$user_agent = $_SERVER['HTTP_USER_AGENT'];

			$lasdCom = $Comments['0'];
			$lasdLive = $LivePic['0'];
			$menusss = pdo_fetchAll('select * from '.tablename('wxz_wzb_live_menu').' where rid='.$rid.' and isshow=1 order by sort desc');

			$menunums = count($menusss);
			if(pdo_tableexists('wxz_lzl_goods')){

				$menus = pdo_fetch('select * from '.tablename('wxz_wzb_live_menu').' where rid='.$rid.' and type=7 and isshow=1 order by sort desc');
				$mch_ids = iunserializer($menus['settings']);

				if(isset($mch_ids['mch_id']) && $mch_ids['mch_id']>0){
					$goodslist = pdo_fetchall("SELECT * FROM " . tablename('wxz_lzl_goods') . " WHERE weid = '{$_W['uniacid']}' and is_delete=0 AND is_on_sale = '1' and mch_id = ".$mch_ids['mch_id']." ORDER BY sort_order DESC, sales DESC LIMIT 50");
				}else{
					$goodslist = pdo_fetchall("SELECT * FROM " . tablename('wxz_lzl_goods') . " WHERE weid = '{$_W['uniacid']}' and is_delete=0 AND is_on_sale = '1' ORDER BY sort_order DESC, sales DESC LIMIT 50");
				}


				//商品列表
				
				foreach($goodslist as $k=>$v) {
						$goodslist[$k]['goods_price'] = $this->get_final_price($v['goods_id']);;
						//判断货品
						$goodslist[$k]['is_spec'] = 0;
						$propertiesArr = $this->get_goods_properties($v['goods_id']);  // 获得商品的规格和属性
						$specification = $propertiesArr['spe'];
						if(!empty($specification)) {
								$goodslist[$k]['is_spec'] = 1;
								$sql = "SELECT SUM(goods_number) FROM ". tablename('wxz_lzl_cart') ." WHERE goods_id ='". $v['goods_id'] ."' AND from_user = '". $_W['fans']['from_user'] ."'";
								$goodslist[$k]['book_num'] = pdo_fetchcolumn($sql);
						}else{
							$sql = "SELECT rec_id,goods_number FROM ". tablename('wxz_lzl_cart') ." WHERE goods_id ='". $v['goods_id'] ."' AND from_user = '". $_W['fans']['from_user'] ."'";
							$row = pdo_fetch($sql);
							$goodslist[$k]['book_num'] = $row['goods_number'];
							$goodslist[$k]['rec_id'] = $row['rec_id'];
						}
				}
				$sql = "SELECT SUM(goods_number) AS number, SUM(goods_number*goods_price) AS amount FROM " . tablename('wxz_lzl_cart') . " where weid = '{$_W['uniacid']}' and from_user='{$_W['fans']['from_user']}' and rec_type=0";
				$cart_total = pdo_fetch($sql);
			}

			if(time()< $item['start_at']){
				$diff = $item['start_at'] - time();
			}

			include $this->template('index2');exit;
		}else{

			$sql='SELECT id,uid,nickname,headimgurl,dateline,content,ispacket,tonickname,touid,dsid,giftid,giftnum,giftpic,ispic FROM ' . tablename('wxz_wzb_comment') .' WHERE rid = '.$rid.' and is_auth=1 order by id desc limit 0,15';
			$Comments = pdo_fetchall($sql);
			krsort($Comments);
			$Comments = array_values($Comments);
			foreach($Comments as $key => $v){
				
				if($v['giftid'] > 0){
					$content = $v['nickname'].'送出了<img src="'.$v['giftpic'].'" width="45px" style="position: absolute;top: -15px;"><span style="margin-left:50px">x'.$v['giftnum'].'</span>';
					$Comments[$key]['content'] = $content;
					$Comments[$key]['type'] = 'gift';
				}elseif($v['dsid'] > 0){
					if($v['touid']==0){
						$content = $v['nickname'].'给主播打赏了1个<span>红包</span>';
					}else{
						$content = $v['nickname'].'给'.$v['tonickname'].'打赏了1个<span>红包</span>';
					}
					$Comments[$key]['content'] = $content;
					$Comments[$key]['type'] = 'reward';
				}elseif($v['ispacket'] == 1){
					$Comments[$key]['type'] = 'grouppacket';
				}else{
					foreach($emoIndex as $k => $va){

						if(strpos($v['content'],$k) !== false){
							$Comments[$key]['content'] = str_replace($k, "<img class='emojia' src='".$emo[$va]['1']."'/>", $v['content']);
						}
					}
					$Comments[$key]['type'] = 'comment';
				}
			}

			$spread = pdo_fetch('SELECT * FROM ' . tablename('wxz_wzb_spread_adv') . ' WHERE `uniacid` = :uniacid and `rid` = :rid and `type` = :type', array(':uniacid' => $_W['uniacid'],':rid' => $rid,':type' => 1));

			$roll = pdo_fetch('SELECT * FROM ' . tablename('wxz_wzb_roll_adv') . ' WHERE `uniacid` = :uniacid and `rid` = :rid and `type` = :type', array(':uniacid' => $_W['uniacid'],':rid' => $rid,':type' => 1));

			
			$setting = pdo_fetch('SELECT * FROM ' . tablename('wxz_wzb_setting') . ' WHERE `uniacid` = :uniacid and `rid` = :rid', array(':uniacid' => $_W['uniacid'],':rid' => $rid));

			if($spread['type']==1 && $time==0){
				include $this->template('spread');exit;
			}

			$sql ='SELECT b.nickname,b.headimgurl,count(*) as c FROM '.tablename('wxz_wzb_help').' as a inner join '.tablename('wxz_wzb_user').' as b on a.share_uid = b.id  inner join ' . tablename('wxz_wzb_viewer') . ' as c on b.id=c.uid and a.rid=c.rid where a.help_uid!=0 and a.rid='.$rid.' and  a.uniacid = '.$_W['uniacid'].' group by a.share_uid order by c desc';
			$help_user_count = pdo_fetchall($sql);

			$sql='SELECT b.nickname,b.id,b.headimgurl,a.amount FROM ' . tablename('wxz_wzb_viewer') . ' as a inner JOIN ' . tablename('wxz_wzb_user') . ' AS b ON a.uid=b.id WHERE a.rid='.$rid.' and a.amount>0 order by a.amount desc';
			$user_amount = pdo_fetchall($sql);


			$sql='SELECT b.nickname,b.id,b.headimgurl,sum(a.fee) as fee FROM ' . tablename('wxz_wzb_ds') . ' as a inner JOIN ' . tablename('wxz_wzb_user') . ' AS b ON a.uid=b.id WHERE a.rid='.$rid.' and a.fee>0 and a.status=1 group by uid ORDER BY fee DESC';
			$dsrank = pdo_fetchall($sql);

			$total_num = $this->num($item);

			$gift = pdo_fetchAll('SELECT * FROM ' . tablename('wxz_wzb_gift').' where rid = '.$rid.' and isshow=1 order by sort asc');
			$user_agent = $_SERVER['HTTP_USER_AGENT'];
			$menusss = pdo_fetchAll('select * from '.tablename('wxz_wzb_live_menu').' where rid='.$rid.' and isshow=1 order by sort desc');
			$menunums = count($menusss);

			if(time()< $item['start_at']){
				$diff = $item['start_at'] - time();
			}
			$pid = pdo_fetch('SELECT id FROM ' . tablename('wxz_wzb_polling') . ' WHERE `rid` = :rid order by id desc limit 1', array(':rid' => $rid));
			
			$roomadmins = (pdo_fetchall('SELECT uid FROM ' . tablename('wxz_wzb_viewer') . ' WHERE `rid` = :rid and role =2 ', array(':rid' => $rid)));
			$roomadmin = json_encode($roomadmins);
			$blacklist = (pdo_fetch('SELECT touid FROM ' . tablename('wxz_wzb_blacklist') . ' WHERE `rid` = :rid and type =1 and touid=:touid ', array(':rid' => $rid,'touid'=>$uid)));
			if(empty($blacklist)){
				$shutup = 0;
			}else{
				$shutup =1;
			}
			$totalzannum = pdo_fetchcolumn("SELECT sum(num) FROM ".tablename('wxz_wzb_zannum')." where `rid` = :rid and uniacid = :uniacid", array(':rid' => $rid,':uniacid' => $_W['uniacid']) );
			if(!$totalzannum){
				$totalzannum  = 0;
			}
			
			if(strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone')||strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')){
				include $this->template('2/indexios');exit;
			}else if(strpos($_SERVER['HTTP_USER_AGENT'], 'Android')){
				include $this->template('2/index2androd');exit;
			}else{
				include $this->template('2/indexios');exit;
			}
		}
		
	}

	public function doMobileBalance() {
		global $_W,$_GPC;
		$uid=$_GPC['wxz_wzb_user'.$_W['uniacid']];
		$rid = $_GPC['rid'];
		$user = pdo_fetch('SELECT * FROM ' . tablename('wxz_wzb_user') . ' WHERE `uniacid` = :uniacid AND `id` = :uid', array(':uniacid' => $_W['uniacid'],':uid' => $uid) );
		$viewer = pdo_fetch('SELECT * FROM ' . tablename('wxz_wzb_viewer') . ' WHERE `uid` = :uid AND `rid` = :rid', array(':uid' => $uid,':rid' => $rid) );
		include $this->template('2/person');
	}

	protected function num($item){
		global $_W,$_GPC;
		
		$total = $item['total_num']== 0 ? $item['base_num'] : $item['total_num'];
		$float= $item['num_float'];
		$num = rand(0,$float);
		if($item['float_type']==1){
			$data = array(
				'total_num'=>$total+$num+1
			);
		}else{
			$Symbol = array('-','+');
			if(array_rand($Symbol) == 1){
				$data = array(
					'total_num'=>$total+$num+1
				);
			}else{
				$data = array(
					'total_num'=>$total-$num+1
				);
				if($data['total_num']<=0){
					$data['total_num'] = 0;
				}
			}
		}
		pdo_update('wxz_wzb_live_setting', $data, array('id' => $item['id']));
		return $data['total_num'];
	}

	public function doMobileLimit(){
		global $_GPC,$_W;
		$uid=$_GPC['wxz_wzb_user'.$_W['uniacid']];
		$pass=$_GPC['password'];
		$type = $_GPC['type'];
		$rid = $_GPC['rid'];

		$item = pdo_fetch('SELECT * FROM ' . tablename('wxz_wzb_live_setting') . ' WHERE `uniacid` = :uniacid and `rid` = :rid', array(':uniacid' => $_W['uniacid'],':rid' => $rid));
		$lid = $item['id'];
		if($item['limit'] == '1' || $item['limit'] == '4'){
			if($item['password'] != $pass){
				$result['s'] = -1;
				$result['msg'] = '密码错误';
				echo json_encode($result);exit;
			}
		}
		if($item['limit'] == '1'){
			$data['password'] = $pass;
			pdo_update('wxz_wzb_viewer', $data, array('rid' => $rid,'uid' => $uid));
			$result['s'] = 1;
			$result['msg'] = '密码正确';
			echo json_encode($result);exit;
			
		}

		if($item['limit'] == '2' || $item['limit'] == '3'){

			$log = pdo_fetch("SELECT * FROM ".tablename('wxz_wzb_paylog')." WHERE rid = :rid AND uid = :uid AND uniacid = :uniacid AND type = :type", array(':rid' => $rid,':uid' => $uid,':type' => 1,':uniacid' => $_W['uniacid']));

			if(!$log){
				$log['rid'] = $rid;
				$log['amount'] = $item['amount'];
				$log['uid'] = $uid;
				$log['uniacid'] = $_W['uniacid'];
				$log['lid'] = $lid;
				$log['type'] = 1;
				$log['status'] = 0;
				$log['dateline'] = time();
				
				pdo_insert('wxz_wzb_paylog', $log);
				$logid=pdo_insertid();
			}else{
				$logid = $log['id'];
			}

			echo json_encode($this->limitPay($item['amount'],$logid,$rid));exit;
		}

		if($item['limit'] == '4'){

			$log = pdo_fetch("SELECT * FROM ".tablename('wxz_wzb_paylog')." WHERE rid = :rid AND uid = :uid AND uniacid = :uniacid AND type = :type", array(':rid' => $rid,':uid' => $uid,':type' => 1,':uniacid' => $_W['uniacid']));

			if(!$log){
				$log['rid'] = $rid;
				$log['amount'] = $item['amount'];
				$log['uid'] = $uid;
				$log['uniacid'] = $_W['uniacid'];
				$log['lid'] = $lid;
				$log['type'] = 1;
				$log['status'] = 0;
				$log['dateline'] = time();
				
				pdo_insert('wxz_wzb_paylog', $log);
				$logid=pdo_insertid();
			}else{
				$logid = $log['id'];
			}
			
			$r = $this->limitPay($item['amount'],$logid,$rid);
			if($r['s']=='-1'){
				echo json_encode($r);exit;
			}else{
				$data['password'] = $pass;
				pdo_update('wxz_wzb_viewer', $data, array('rid' => $rid,'uid' => $uid));
				echo json_encode($r);
			}
		}
	}

	public function doMobileLimit2(){
		global $_GPC,$_W;
		$uid=$_GPC['wxz_wzb_user'.$_W['uniacid']];
		$pass=$_GPC['password'];
		$type = $_GPC['type'];
		$rid = $_GPC['rid'];

		$item = pdo_fetch('SELECT * FROM ' . tablename('wxz_wzb_live_setting') . ' WHERE `uniacid` = :uniacid and `rid` = :rid', array(':uniacid' => $_W['uniacid'],':rid' => $rid));
		
		if($item['limit'] == '1' || $item['limit'] == '4'){
			if($item['password'] != $pass){
				$result['s'] = -1;
				$result['msg'] = '密码错误';
				echo json_encode($result);exit;
			}
		}
		if($item['limit'] == '1'){
			$data['password'] = $pass;
			pdo_update('wxz_wzb_viewer', $data, array('rid' => $rid,'uid' => $uid));
			$result['s'] = 1;
			$result['msg'] = '密码正确';
			echo json_encode($result);exit;
			
		}

		if($item['limit'] == '2' || $item['limit'] == '3'){

			$log = pdo_fetch("SELECT * FROM ".tablename('wxz_wzb_paylog')." WHERE rid = :rid AND uid = :uid AND uniacid = :uniacid AND type = :type", array(':rid' => $rid,':uid' => $uid,':type' => 1,':uniacid' => $_W['uniacid']));

			if(!$log){
				$log['rid'] = $rid;
				$log['amount'] = $item['amount'];
				$log['uid'] = $uid;
				$log['uniacid'] = $_W['uniacid'];
				$log['lid'] = $lid;
				$log['type'] = 1;
				$log['status'] = 0;
				$log['dateline'] = time();
				
				pdo_insert('wxz_wzb_paylog', $log);
				$logid=pdo_insertid();
			}else{
				$logid = $log['id'];
			}
			$result = array('s'=>'1','amount'=>$item['amount'],'logid'=>$logid,'rid'=>$rid);
			echo json_encode($result);exit;
		}

		if($item['limit'] == '4'){

			$log = pdo_fetch("SELECT * FROM ".tablename('wxz_wzb_paylog')." WHERE rid = :rid AND uid = :uid AND uniacid = :uniacid AND type = :type", array(':rid' => $rid,':uid' => $uid,':type' => 1,':uniacid' => $_W['uniacid']));

			if(!$log){
				$log['rid'] = $rid;
				$log['amount'] = $item['amount'];
				$log['uid'] = $uid;
				$log['uniacid'] = $_W['uniacid'];
				$log['lid'] = $lid;
				$log['type'] = 1;
				$log['status'] = 0;
				$log['dateline'] = time();
				
				pdo_insert('wxz_wzb_paylog', $log);
				$logid=pdo_insertid();
			}else{
				$logid = $log['id'];
			}
			$result = array('s'=>'1','amount'=>$item['amount'],'logid'=>$logid,'rid'=>$rid);
			echo json_encode($result);exit;
			
		}
	}

	public function limitPay($money,$logid,$rid){
		global $_W,$_GPC;

		$uid=$_GPC['wxz_wzb_user'.$_W['uniacid']];
		$params = array(
			'fee' =>$money,
			'user' =>$_W['openid'],
			'title' =>urldecode($content),
			'random'=>random(16).$logid,
		);
		$user = pdo_fetch('SELECT * FROM ' . tablename('wxz_wzb_user') . ' WHERE `uniacid` = :uniacid AND `id` = :uid', array(':uniacid' => $_W['uniacid'],':uid' => $uid) );
		$setting = uni_setting($_W['uniacid'], array('payment'));
		if($setting['payment']['wechat']['switch']==2){
			$setting = uni_setting($setting['payment']['wechat']['borrow'], array('payment'));
		}
		if(!is_array($setting['payment']['wechat'])) {
			pdo_delete('wxz_wzb_paylog',array('id'=>$logid));
			$result = array('s'=>'-1','msg'=>'信息出错！');
			return $result;
		}

		$wechat = $setting['payment']['wechat'];

		$sql = 'SELECT `key`,`secret` FROM ' . tablename('account_wechats') . ' WHERE `acid`=:acid';
		$row = pdo_fetch($sql, array(':acid' => $wechat['account']));
		$wechat['appid'] = $row['key'];
		$wechat['secret'] = $row['secret'];
		
		if ($wechat['version'] == 1) {
			$option = array();
			$option['appId'] = $wechat['appid'];
			$option['timeStamp'] = time();
			$option['nonceStr'] = random(8);
			$package = array();
			$package['bank_type'] = 'WX';
			$package['body'] = '付费直播';
			$package['attach'] = $_W['uniacid'];
			$package['partner'] = $wechat['partner'];
			$package['out_trade_no'] = $params['random'];
			$package['total_fee'] = $params['fee'];
			$package['fee_type'] = '1';
			$package['notify_url'] = $_W['siteroot'] . 'addons/wxz_wzb/limit.php';
			$package['spbill_create_ip'] = CLIENT_IP;
			$package['time_start'] = date('YmdHis', time());
			$package['time_expire'] = date('YmdHis', time() + 600);
			$package['input_charset'] = 'UTF-8';
			ksort($package);
			$string1 = '';
			foreach($package as $key => $v) {
				if (empty($v)) {
					continue;
				}
				$string1 .= "{$key}={$v}&";
			}
			$string1 .= "key={$wechat['key']}";
			$sign = strtoupper(md5($string1));

			$string2 = '';
			foreach($package as $key => $v) {
				$v = urlencode($v);
				$string2 .= "{$key}={$v}&";
			}
			$string2 .= "sign={$sign}";
			$option['package'] = $string2;

			$string = '';
			$keys = array('appId', 'timeStamp', 'nonceStr', 'package', 'appKey');
			sort($keys);
			foreach($keys as $key) {
				$v = $option[$key];
				if($key == 'appKey') {
					$v = $wechat['signkey'];
				}
				$key = strtolower($key);
				$string .= "{$key}={$v}&";
			}
			$string = rtrim($string, '&');

			$option['signType'] = 'SHA1';
			$option['paySign'] = sha1($string);
			if (is_error($option)) {
				pdo_delete('wxz_wzb_paylog',array('id'=>$logid));
				$result = array('s'=>'-1','msg'=>'信息出错！');
				return $result;
			}
			$result = array('s'=>'-1','msg'=>$option);
			return $result;

		} else {

			$package = array();
			$package['appid'] = $wechat['appid'];
			$package['mch_id'] = $wechat['mchid'];
			$package['nonce_str'] = random(8);
			$package['body'] = '付费直播';
			$package['attach'] = $_W['uniacid'];
			$package['out_trade_no'] = $params['random'];
			$package['total_fee'] = $params['fee'];
			$package['spbill_create_ip'] = CLIENT_IP;
			$package['time_start'] = date('YmdHis', time());
			$package['time_expire'] = date('YmdHis', time() + 600);
			$package['notify_url'] = $_W['siteroot'] . 'addons/wxz_wzb/limit.php';
			$package['trade_type'] = 'JSAPI';
			$package['openid'] = $user['openid'];

			ksort($package, SORT_STRING);
			$string1 = '';
			foreach($package as $key => $v) {
				if (empty($v)) {
					continue;
				}
				$string1 .= "{$key}={$v}&";
			}
			$string1 .= "key={$wechat['signkey']}";
			$package['sign'] = strtoupper(md5($string1));
			$dat = array2xml($package);
			$response = ihttp_request('https://api.mch.weixin.qq.com/pay/unifiedorder', $dat);
			
			if (is_error($response)) {
				$result = array('s'=>'-1','msg'=>strval($xml->return_msg));
			
				return $result;
			}
			
			$xml = @isimplexml_load_string($response['content'], 'SimpleXMLElement', LIBXML_NOCDATA);
					
			if (strval($xml->return_code) == 'FAIL') {
				$result = array('s'=>'-1','msg'=>strval($xml->return_msg));
			
				return $result;

			}

			if (strval($xml->result_code) == 'FAIL') {
				$result = array('s'=>'-1','msg'=>strval($xml->return_msg));
			
				return $result;

			}
			$prepayid = $xml->prepay_id;
			$option['appId'] = $wechat['appid'];
			$option['timeStamp'] = TIMESTAMP;
			$option['nonceStr'] = random(8);
			$option['package'] = 'prepay_id='.$prepayid;
			$option['signType'] = 'MD5';
			ksort($option, SORT_STRING);
			foreach($option as $key => $v) {
				$string .= "{$key}={$v}&";
			}
			$string .= "key={$wechat['signkey']}";
			$option['paySign'] = strtoupper(md5($string));
			
			$result = array('s'=>'1','msg'=>$option);
			
			return $result;
		}
	}

	public function doMobilePass(){
		global $_GPC, $_W;
		
		$uid=$_GPC['wxz_wzb_user'.$_W['uniacid']];
		$pass=$_GPC['password'];
		$rid = $_GPC['rid'];

		$item = pdo_fetch('SELECT * FROM ' . tablename('wxz_wzb_live_setting') . ' WHERE `uniacid` = :uniacid and `rid` = :rid', array(':uniacid' => $_W['uniacid'],':rid' => $rid));

		if($item['limit'] == '1'){
			if($item['password'] == $pass){
				$data['password'] = $pass;
				pdo_update('wxz_wzb_viewer', $data, array('rid' => $rid,'uid' => $uid));
				$result['status'] = 1;
				$result['msg'] = '密码正确';
				echo json_encode($result);
			}else{
				$result['status'] = -1;
				$result['msg'] = '密码错误';
				echo json_encode($result);
			}
			
		}else{
			$result['status'] = 1;
			$result['msg'] = '不需要密码';
			echo json_encode($result);
		}
	}

	public function doMobilePay(){
		global  $_W, $_GPC;
		
		$rid = intval($_GPC['rid']);
		$fee = intval($_GPC['fee']);
		$lid = intval($_GPC['lid']);
		$uid=$_GPC['wxz_wzb_user'.$_W['uniacid']];

		$log = pdo_fetch("SELECT * FROM ".tablename('wxz_wzb_paylog')." WHERE rid = :rid AND uid = :uid AND uniacid = :uniacid AND type = :type", array(':rid' => $rid,':uid' => $uid,':type' => 1,':uniacid' => $_W['uniacid']));

		if(!$log){
			$log['rid'] = $rid;
			$log['amount'] = $fee;
			$log['uid'] = $uid;
			$log['uniacid'] = $_W['uniacid'];
			$log['lid'] = $lid;
			$log['type'] = 1;
			$log['status'] = 0;
			$log['dateline'] = time();
			
			pdo_insert('wxz_wzb_paylog', $log);
			$logid=pdo_insertid();
		}else{
			$logid = $log['id'];
		}

		$fee = $fee/100;
		$params['tid'] = $logid;
		$params['user'] = $_W['fans']['from_user'];
		$params['fee'] = $fee;
		$params['title'] = $_W['account']['name'];
		$params['ordersn'] = date('YmdHis').'-'.$_W['fans']['uid'];

		$this->pay($params);exit;
	}

	public function payResult($params) {
		
		$log = pdo_fetch("SELECT * FROM ".tablename('wxz_wzb_paylog')." WHERE id = :id", array(':id' => $params['tid']));
		if ($params['result'] == 'success' && $params['from'] == 'notify') {
			$fee = $params['fee'];
			$total_fee = $fee;
			$data = array('status' => $params['result'] == 'success' ? 1 : -1);
			if ($params['type'] == 'wechat') {
				$data['transid'] = $params['tag']['transaction_id'];
			}
			pdo_update('wxz_wzb_paylog', $data, array('id' => $params['tid']));
			if ($params['fee'] != $log['fee']) {
				exit('用户支付的金额与订单金额不符合');
			}
		}

		if ($params['from'] == 'return') {
			if ($params['result'] == 'success') {
				message('支付成功！', $this->createMobileUrl('index', array('rid' => $log['rid'])), 'success');
			} else {
				message('支付失败！', $this->createMobileUrl('index', array('rid' => $log['rid'])), 'error');
			}
		}
	}

	public function ipAuth($getAdr,$type,$rid,$ip){
        global $_GPC, $_W;
        $allowArea = explode(",",$getAdr);
				
		require_once MODULE_ROOT."/getip/IP.class.php";

        $ip = IP::find($ip);

        switch($type){
            case 1:
                if(!in_array($ip[2],$allowArea)){
                    return false;
                }
				break;
            case 2:
                if(in_array($ip[2],$allowArea)){
                    return false;
                }
				break;
            default:
				return true;
        }
		return true;
    }

	//发送红包
	public function doMobileSends(){
		
        global $_W,$_GPC;
		
        $uniacid = $_W['uniacid'];
        $rid = $_GPC['rid'];
        $hb_id = $_GPC['hb_id'];
		$hb_msg = pdo_fetch('SELECT * FROM ' . tablename('wxz_wzb_comment') . ' WHERE `uniacid` = :uniacid AND `id` = :hb_id AND `rid` = :rid', array(':uniacid' => $_W['uniacid'],':hb_id' => $hb_id,':rid' => $rid) );

		$uid=$_GPC['wxz_wzb_user'.$_W['uniacid']];

		$user = pdo_fetch('SELECT * FROM ' . tablename('wxz_wzb_user') . ' WHERE `uniacid` = :uniacid AND `id` = :uid', array(':uniacid' => $_W['uniacid'],':uid' => $uid) );

		$viewer = pdo_fetch("select * from ".tablename('wxz_wzb_viewer')." where rid=".$rid." and uid=".$user['id']);
		$log = pdo_fetch("SELECT * FROM ".tablename('wxz_wzb_tx')." WHERE rid = :rid AND uid = :uid AND uniacid = :uniacid AND type = :type AND fromid = :fromid", array(':rid' => $rid,':uid' => $uid,':fromid' => $hb_msg['id'],':type' => 3,':uniacid' => $_W['uniacid']));
		
		$moneys = iunserializer($hb_msg['samount']);
		
		
		if($log){
			$res['type']=-1;//未关注
			$res['msg']='你已经领过该红包';//未关注
			echo json_encode($res);
			exit();
		}

		if($hb_msg['num']==$hb_msg['send_num'] || empty($moneys)){
			$res['type']=-1;//未关注
			$res['msg']='红包已发完';//未关注
			echo json_encode($res);
			exit();
		}

		$fee = array_pop($moneys);
		if($hb_msg['syifa']==''){
			$syifa = array('0'=>$fee);
		}else{
			$syifa = iunserializer($hb_msg['syifa']);
			$syifa[] = $fee;
		}
		//$fee = $this->randBonus(($hb_msg['amount']-$hb_msg['yifa_amount']),($hb_msg['num']-$hb_msg['send_num']),$hb_msg['type']);
		
		if($hb_msg['yifa_amount']>=$hb_msg['amount'] || $hb_msg['send_num']>=$hb_msg['num']){
			$res['type']=-1;
			$res['msg']='红包已领完';
			echo json_encode($res);
			exit();
		}

		if($fee>$hb_msg['amount']){
			$res['type']=-1;
			$res['msg']='你的提现有点多';
			echo json_encode($res);
			exit();
		}
		
		$rec = array();
		$rec['uid'] = $user['id'];
		$rec['uniacid'] = $_W['uniacid'];
		$rec['fee'] = floatval($fee/100);
		$rec['dateline'] = TIMESTAMP;
		$rec['rid'] = $rid;
		$rec['fromid'] = $hb_msg['id'];
		$rec['type'] = 3;
		pdo_insert('wxz_wzb_tx', $rec);

		$logid=pdo_insertid();
		$user_amount['amount'] = ($fee)+$viewer['amount'];
		pdo_update('wxz_wzb_viewer', $user_amount, array('uid'=>$uid,'rid'=>$rid));
		pdo_update('wxz_wzb_comment', array('send_num'=>$hb_msg['send_num']+1,'yifa_amount'=>$hb_msg['yifa_amount']+$fee,'samount'=>iserializer($moneys),'syifa'=>iserializer($syifa)), array('id'=>$hb_msg['id']));
		$data2=array(
			'uniacid'=>$_W['uniacid'],
			'uid'=>$uid,
			'ip'=>$_W['clientip'],
			'is_auth'=>1,
			'nickname'=>$user['nickname'],
			'headimgurl'=>$user['headimgurl'],
			'rid'=>$rid,
			'content'=>$_GPC['content'],
			'toid'=>$hb_msg['id'],
			'touid'=>$hb_msg['uid'],
			'num'=>1,
			'ispacket'=>1,
			'type'=>$hb_msg['type'],
			'tonickname'=>$hb_msg['nickname'],
			'amount'=>$fee,
			'toheadimgurl'=>$hb_msg['headimgurl'],
			'dateline'=>time()
		);
		pdo_insert('wxz_wzb_comment',$data2);
		$html = '<p><img  src="'.MODULE_URL.'template/mobile/img/mini_hongbao.png" />'.$user['nickname'].'领取了<span style="color:#FF0000;">红包</span></p>';
		$res['type']=1;//未关注
		$res['msg']='领取成功';//未关注
		$res['rhtml']=$html;//未关注
		$r = $this->alrecy($rid,$hb_id);

		echo json_encode($res+$r);
    }

	public function doMobileGethb(){
		global $_W,$_GPC;
		
        $uniacid = $_W['uniacid'];
        $rid = $_GPC['rid'];
        $hb_id = $_GPC['hb_id'];
		$r = $this->alrecy($rid,$hb_id);

		echo json_encode($r);
	}

	public function alrecy($rid,$hb_id){
		global $_W,$_GPC;
		
		$hb_msg = pdo_fetch('SELECT * FROM ' . tablename('wxz_wzb_comment') . ' WHERE `uniacid` = :uniacid AND `id` = :hb_id AND `rid` = :rid', array(':uniacid' => $_W['uniacid'],':hb_id' => $hb_id,':rid' => $rid) );

		$uid=$_GPC['wxz_wzb_user'.$_W['uniacid']];

		$user = pdo_fetch('SELECT * FROM ' . tablename('wxz_wzb_user') . ' WHERE `uniacid` = :uniacid AND `id` = :uid', array(':uniacid' => $_W['uniacid'],':uid' => $uid) );

		$my = pdo_fetch("SELECT * FROM ".tablename('wxz_wzb_comment')." WHERE rid = :rid AND uid = :uid AND uniacid = :uniacid AND toid = :toid", array(':rid' => $rid,':uid' => $uid,':toid' => $hb_id,':uniacid' => $_W['uniacid']));

		$viewer = pdo_fetch("select * from ".tablename('wxz_wzb_viewer')." where rid=".$rid." and uid=".$user['id']);
		$log = pdo_fetchAll("SELECT * FROM ".tablename('wxz_wzb_comment')." WHERE rid = :rid AND uniacid = :uniacid AND toid = :toid", array(':rid' => $rid,':toid' => $hb_id,':uniacid' => $_W['uniacid']));
		foreach($log as $key => $v){
			$html .= '<li>';
			$html .= '<div class="hongbao_list_heard"><img src="'.$v['headimgurl'].'" style="width:42px;"/></div>';
			$html .= '<div class="hongbao_list_font">'.($v['amount']/100).'元</div>';
			$html .= '<div style="padding-left:70px; line-height:40px; font-size:14px;height:40px;">'.$v['nickname'].'</div>';
			$html .= '</li>';
		}
		$name = $hb_msg['nickname'];
		$name = $hb_msg['headimgurl'];
		$res['name']=$hb_msg['nickname'];
		$res['amount']=$my['amount']/100;
		$res['num']=$hb_msg['send_num'].'/'.$hb_msg['num'];
		$res['headimgurl']=tomedia($hb_msg['headimgurl']);
		$res['hhtml']=$html;
		return $res;
	}
	
	public function intoroom($rid,$user){
		global $_W,$_GPC;
		
		$viewer = pdo_fetch("select * from ".tablename('wxz_wzb_viewer')." where rid=".$rid." and uid=".$user['id']);
		if(!$viewer){
			$data['rid'] = $rid;
			$data['uniacid'] = $_W['uniacid'];
			$data['uid'] = $user['id'];
			$data['dateline'] = time();
			pdo_insert('wxz_wzb_viewer',$data);
		}
	}

	//认证
	public function doMobileAuth2() {
        global $_W,$_GPC;
		
		$sub_openid = $_GPC['sub_openid'];
		$share_uid = $_GPC['share_uid'];
		$rid = $_GPC['rid'];
		$back_url = isset($_GPC['back_url']) ? $_GPC['back_url'] : 'index';
		
		$uid=$_GPC['wxz_wzb_user'.$_W['uniacid']];
		$item = pdo_fetch("select * from ".tablename('wxz_wzb_setting')." where rid = ".$rid." and uniacid = ".$_W['uniacid']);

        $return_url = $_W['siteroot'].'app/' . (substr($this->createMobileurl($back_url,array('sub_openid'=>$sub_openid,'share_uid'=>$share_uid,'rid'=>$rid)), 2));
        if(empty($_GPC['code'])){

			$back_url = $_W['siteroot'].'app/' . (substr($this->createMobileurl('auth',array('sub_openid'=>$sub_openid,'share_uid'=>$share_uid,'rid'=>$rid,'back_url'=>$back_url)), 2));
			//服务号appid 待创建
            $url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$item['loan_appid']."&redirect_uri=".urlencode($back_url)."&response_type=code&scope=snsapi_userinfo&is_authe=STATE#wechat_redirect";
			header("location:".$url);
            exit;
        }
        $param=array();
        $param ['appid']=$item['loan_appid']; //服务号appid
        $param ['secret'] = $item['loan_secret'];//服务号secret
        $param ['code'] = $_GPC['code'];
        $param ['grant_type'] = 'authorization_code';
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?' . http_build_query ( $param );
		
        $content = file_get_contents ( $url );
        $content = json_decode ( $content, true );        
		
        $param=array();
        $param ['access_token'] = $content ['access_token'];
        $param ['openid'] = $content ['openid'];
        $param ['lang'] = 'zh_CN';
        $url = 'https://api.weixin.qq.com/sns/userinfo?' . http_build_query ( $param );
        $content = file_get_contents ( $url );
        $wxuser = json_decode ( $content, true );

		if(!$wxuser['openid']){
            header("location: $url");
            exit;
        }

        $user = pdo_fetch('SELECT * FROM ' . tablename('wxz_wzb_user') . ' WHERE `uniacid` = :uniacid AND `openid` = :openid', array(':uniacid' => $_W['uniacid'],':openid' => $wxuser['openid']) );
		
		if(!$sub_openid && $user['sub_openid']){
			$sub_openid = $user['sub_openid'];
		}
        $data=array(
            'uniacid'=>$_W['uniacid'],
            'nickname'=>$wxuser['nickname'],
            'headimgurl'=>$wxuser['headimgurl'],
            'province'=>$wxuser['province'],
            'ip'=>$_W['clientip'],
            'city'=>$wxuser['city'],
            'sex'=>$wxuser['sex'],
            'dateline'=>time(),
            'sub_openid'=>$sub_openid,
            'openid'=>$wxuser['openid']
        );
        if($user){
            pdo_update('wxz_wzb_user', $data, array('id' => $user['id']));
        }else{
            pdo_insert('wxz_wzb_user', $data);
            $user['uid']=pdo_insertid();
        }
        isetcookie('wxz_wzb_user'.$_W['uniacid'], $user['id']);
		
        header("location: $return_url");
        exit;
    }

	public function ip(){
		
		load()->func('communication');
		$content = ihttp_request("http://ip.taobao.com/service/getIpInfo.php?ip=".CLIENT_IP);		
		$info = @json_decode($content['content'], true);
		return $info;
	}

	public function authss(){
		global $_W,$_GPC;
		
		$sub_openid = $_GPC['sub_openid'];
		$share_uid = $_GPC['share_uid'];

		$back_url = isset($_GPC['back_url']) ? $_GPC['back_url'] : 'index';
		
		$uid=$_GPC['wxz_wzb_user'.$_W['uniacid']];
		
		$user_agent = $_SERVER['HTTP_USER_AGENT'];

		
		if (strpos($user_agent, 'MicroMessenger') === false) {
			$openid = $ip = CLIENT_IP;
			
			$user =  pdo_fetch("SELECT * FROM ".tablename('wxz_wzb_user')." WHERE sub_openid = '".$ip."' AND uniacid=".$_W['uniacid']);

			if(empty($user)){
				if(empty($ip)){
					header("location:$gz_url");
					exit;
				}
				$ip_address = $this->ip();

				if($ip_address['code']!=0){
						header("location:$gz_url");
						exit;
				}
				$web_data = array(
					'sub_openid' =>$ip,
					'uniacid'=>$_W['uniacid'],
					'province'=>mb_substr($ip_address['data']['region'],0,-1),
					'ip'=>$ip,
					'city'=>mb_substr($ip_address['data']['city'],0,-1),
					'dateline'=>time(),
					'headimgurl'=> tomedia($settings['no_avatar']),
					'nickname'=>empty($ip_address['data']['region'])?'网友':$ip_address['data']['region'].'网友',
					'sex'=>0,
				);
				
				pdo_insert('wxz_wzb_user',$web_data); 
				$user_id = pdo_insertid();

				$user =  pdo_fetch("SELECT * FROM ".tablename('wxz_wzb_user')." WHERE id = :id  AND uniacid=:uniacid",array(':id'=>$user_id,':uniacid' =>$_W['uniacid']));
				
			}
			
			isetcookie('wxz_wzb_user'.$_W['uniacid'], $user['id'],84600);

			return $user['id'];
		} else {
			$openid = $_W['openid'];
			
			if($_W['account']['level']==4){
				$user =  pdo_fetch("SELECT * FROM ".tablename('wxz_wzb_user')." WHERE openid = :openid AND uniacid=:uniacid",array(':openid' =>$openid,':uniacid' =>$_W['uniacid']));
			}else{
				$user =  pdo_fetch("SELECT * FROM ".tablename('wxz_wzb_user')." WHERE sub_openid = :openid AND uniacid=:uniacid",array(':openid' =>$openid,':uniacid' =>$_W['uniacid']));
			}
			
			if(empty($user) || $_W['fans']['follow']==0){
				$data = array(
					'sub_openid' =>$openid,
					'uniacid'=>$_W['uniacid'],
					'dateline'=>time(),
				);
				if($_W['account']['level']<3){
					load()->model('mc');
					$oauth_user = mc_oauth_userinfo();
					if (!is_error($oauth_user) && !empty($oauth_user) && is_array($oauth_user)) {
								$userinfo = $oauth_user;
					}else{
								message("借用oauth失败");
					}
				}elseif($_W['account']['level']==3){
					load()->model('mc');
					$oauth_user = mc_oauth_userinfo();
					if (!is_error($oauth_user) && !empty($oauth_user) && is_array($oauth_user)) {
								$userinfo = $oauth_user;
					}else{
								message("借用oauth失败");
					}
				}else{

						if($_W['fans']['follow']=='1'){
								if($_W['fans']['tag']['subscribe']==1){
									$userinfo = $_W['fans'];
								}else{
									$userinfo = $this->get_follow_fansinfo($openid);
								
									if($userinfo['subscribe']!='1'){
										
										message('获取粉丝信息失败');
									}
								}
								
						}else{
								$oauth_user = mc_oauth_userinfo();
								if (!is_error($oauth_user) && !empty($oauth_user) && is_array($oauth_user)) {
											$userinfo = $oauth_user;
								}else{
											message("借用oauth失败");
								}
						}
				}
				if(!empty($userinfo['headimgurl'])){
					 $data['headimgurl'] = $userinfo['headimgurl'];
				}else{
					if(empty($userinfo['headimgurl'])){
					 $data['headimgurl'] = tomedia($settings['no_headimgurl']);
					}else{
						$data['headimgurl'] = $userinfo['headimgurl'];
					}
				}
				if(empty($userinfo['sex'])){
					$data['sex'] = '0';
				}else{
					$data['sex'] = $userinfo['sex'];
				}
				if(!empty($userinfo['nickname'])){
					$data['nickname'] = $userinfo['nickname'];
				}else{
					$data['nickname'] = '微信昵称无法识别';
				}
				$data['openid'] = $userinfo['openid'];
				$data['province']=$userinfo['tag']['province'];
				$data['ip']=$_W['clientip'];
				$data['city']=$userinfo['tag']['city'];
				if(empty($user)){
					pdo_insert('wxz_wzb_user',$data); 
					$user_id = pdo_insertid();
					$user =  pdo_fetch("SELECT * FROM ".tablename('wxz_wzb_user')." WHERE id = :id  AND uniacid=:uniacid",array(':id'=>$user_id,':uniacid' =>$_W['uniacid']));

				}
			}
			
		}

		isetcookie('wxz_wzb_user'.$_W['uniacid'], $user['id'],84600);
			return $user['id'];
	}

	public function is_mobile(){  
		$agent = strtolower($_SERVER['HTTP_USER_AGENT']);  
		$is_pc = (strpos($agent, 'windows nt')) ? true : false;  
		$is_mac = (strpos($agent, 'mac os')) ? true : false;  
		$is_iphone = (strpos($agent, 'iphone')) ? true : false;  
		$is_android = (strpos($agent, 'android')) ? true : false;  
		$is_ipad = (strpos($agent, 'ipad')) ? true : false;  
		  
		if($is_iphone){  
			  return  true;  
		}  
		if($is_android){  
			  return  true;  
		}  
		if($is_ipad){  
			  return  true;  
		}  
		if($is_pc){  
			  return  false;  
		}   
		if($is_mac){  
			  return  false;  
		}  
	}  


	public function auths($sub_openid,$limit){
		global $_W,$_GPC;
		
		$share_uid = $_GPC['share_uid'];
		$rid = $_GPC['rid'];
		$back_url = isset($_GPC['back_url']) ? $_GPC['back_url'] : 'index';
		
		$uid=$_GPC['wxz_wzb_user'.$_W['uniacid']];
		$settings = pdo_fetch('SELECT * FROM ' . tablename('wxz_wzb_setting') . ' WHERE `uniacid` = :uniacid and `rid` = :rid', array(':uniacid' => $_W['uniacid'],':rid' => $rid));

		$gz_url = $settings['attention_url'];
		
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		$item = pdo_fetch('SELECT * FROM ' . tablename('wxz_wzb_live_setting') . ' WHERE `uniacid` = :uniacid and `rid` = :rid', array(':uniacid' => $_W['uniacid'],':rid' => $rid));
		
		if (strpos($user_agent, 'MicroMessenger') === false || $this->is_mobile()==false) {
			if($limit['limit']>1 || $settings['gz_must']==1){
				include $this->template('wx');exit;
			}
		}
		if (strpos($user_agent, 'MicroMessenger') === false) {

			$openid = $ip = CLIENT_IP;
			
			$user =  pdo_fetch("SELECT * FROM ".tablename('wxz_wzb_user')." WHERE sub_openid = '".$ip."' AND uniacid=".$_W['uniacid']);

			if(empty($user)){
				if(empty($ip)){
					header("location:$gz_url");
					exit;
				}
				$ip_address = $this->ip();

				if($ip_address['code']!=0){
						header("location:$gz_url");
						exit;
				}
				$web_data = array(
					'sub_openid' =>$ip,
					'uniacid'=>$_W['uniacid'],
					'province'=>mb_substr($ip_address['data']['region'],0,-1),
					'ip'=>$ip,
					'city'=>mb_substr($ip_address['data']['city'],0,-1),
					'dateline'=>time(),
					'headimgurl'=> tomedia($settings['no_avatar']),
					'nickname'=>empty($ip_address['data']['region'])?'网友':$ip_address['data']['region'].'网友',
					'sex'=>0,
				);
				
				pdo_insert('wxz_wzb_user',$web_data); 
				$user_id = pdo_insertid();
				$s = array();
				$s['real_num'] = $item['real_num'] + 1;
				pdo_update('wxz_wzb_live_setting', $s, array('id' => $item['id']));
				$user =  pdo_fetch("SELECT * FROM ".tablename('wxz_wzb_user')." WHERE id = :id  AND uniacid=:uniacid",array(':id'=>$user_id,':uniacid' =>$_W['uniacid']));
				
			}
			
			isetcookie('wxz_wzb_user'.$_W['uniacid'], $user['id'],84600);

			return $user['id'];
		} else {
			$openid = $_W['openid'];

			if(empty($openid)){
				//header("location:$gz_url");
				//exit;
				if($gz_url){
					header("location:$gz_url");
					exit;
				}else{
					return -1;
				}
				
			}
			if($_W['account']['level']==4){
				$user =  pdo_fetch("SELECT * FROM ".tablename('wxz_wzb_user')." WHERE openid = :openid AND uniacid=:uniacid",array(':openid' =>$openid,':uniacid' =>$_W['uniacid']));
				$flag = true;
			}else{
				$user =  pdo_fetch("SELECT * FROM ".tablename('wxz_wzb_user')." WHERE sub_openid = :openid AND uniacid=:uniacid",array(':openid' =>$openid,':uniacid' =>$_W['uniacid']));
				if($user['openid'] != $user['sub_openid']){
					$flag = true;
				}else{
					$flag = false;
				}
			}

			//if(empty($user) || $_W['fans']['follow']==0 || $flag==false){
			if(1){
				
				$data = array(
					'sub_openid' =>$openid,
					'uniacid'=>$_W['uniacid'],
					'dateline'=>time(),
				);
				if($_W['account']['level']<3){
					load()->model('mc');
					$oauth_user = mc_oauth_userinfo();
					if (!is_error($oauth_user) && !empty($oauth_user) && is_array($oauth_user)) {
								$userinfo = $oauth_user;
					}else{
								message("借用oauth失败");
					}
				}elseif($_W['account']['level']==3){
					if($settings['gz_must']=='0' || $settings['gz_must']=='2'){
									load()->model('mc');
									$oauth_user = mc_oauth_userinfo();
									if (!is_error($oauth_user) && !empty($oauth_user) && is_array($oauth_user)) {
												$userinfo = $oauth_user;
									}else{
												message("借用oauth失败");
									}
					}else{
						if($_W['fans']['follow']=='1'){
								if($_W['fans']['tag']['subscribe']==1){
									$userinfo = $_W['fans'];
								}else{
									$userinfo = $this->get_follow_fansinfo($openid);
								
									if($userinfo['subscribe']!='1'){
										
										message('获取粉丝信息失败');
									}
								}
								
						}else{
										if($settings['gz_must']=='1'){
											if($gz_url){
												header("location:$gz_url");
												exit;
											}else{
												return -1;
											}
										}
						}
					}
				}else{

						if($_W['fans']['follow']=='1'){
								if($_W['fans']['tag']['subscribe']==1){
									$userinfo = $_W['fans'];
								}else{
									$userinfo = $this->get_follow_fansinfo($openid);
								
									if($userinfo['subscribe']!='1'){
										
										message('获取粉丝信息失败');
									}
								}
								
						}else{
								if($settings['gz_must']=='0' || $settings['gz_must']=='2'){
									$oauth_user = mc_oauth_userinfo();
									if (!is_error($oauth_user) && !empty($oauth_user) && is_array($oauth_user)) {
												$userinfo = $oauth_user;
									}else{
												message("借用oauth失败");
									}
								}else{
									if($settings['gz_must']=='1'){
										if($gz_url){
											header("location:$gz_url");
											exit;
										}else{
											return -1;
										}
									}
									
								}
						}
				}
				if(!empty($userinfo['headimgurl'])){
					 $data['headimgurl'] = $userinfo['headimgurl'];
				}else{
					if(empty($userinfo['headimgurl'])){
					 $data['headimgurl'] = tomedia($settings['no_headimgurl']);
					}else{
						$data['headimgurl'] = $userinfo['headimgurl'];
					}
				}
				if(empty($userinfo['sex'])){
					$data['sex'] = '0';
				}else{
					$data['sex'] = $userinfo['sex'];
				}
				if(!empty($userinfo['nickname'])){
					$data['nickname'] = $userinfo['nickname'];
				}else{
					$data['nickname'] = '微信昵称无法识别';
				}
				$data['openid'] = $userinfo['openid'];
				$data['province']=$userinfo['tag']['province'];
				$data['ip']=$_W['clientip'];
				$data['city']=$userinfo['tag']['city'];
				if(empty($user)){
					pdo_insert('wxz_wzb_user',$data); 
					$user_id = pdo_insertid();
					$user =  pdo_fetch("SELECT * FROM ".tablename('wxz_wzb_user')." WHERE id = :id  AND uniacid=:uniacid",array(':id'=>$user_id,':uniacid' =>$_W['uniacid']));
					$s = array();
					$s['real_num'] = $item['real_num'] + 1;
					pdo_update('wxz_wzb_live_setting', $s, array('id' => $item['id']));
				}else{

					pdo_update('wxz_wzb_user', $data, array('id' => $user['id']));
				}
			}
			
		}

		isetcookie('wxz_wzb_user'.$_W['uniacid'], $user['id'],84600);
			return $user['id'];
	}



	public function get_follow_fansinfo($openid){
		global $_W,$_GPC;
		$access_token = $this->getAccessToken();
		$url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
		load()->func('communication');
		$content = ihttp_request($url);		
		$info = @json_decode($content['content'], true);
		return $info;
	}

	public  function  getAccessToken () {
		global $_W;
		load()->classs('weixin.account');
		$accObj = WeixinAccount::create($_W['acid']);
		//$accObj->clearAccessToken($_W['acid']);
		$access_token = $accObj->fetch_token();
		return $access_token;
	}

	

	function randBonus($total=0, $num=3, $type=1){
		if($type==1){
			$min=0.01;
			$moneys = array();
			for ($i=1;$i<$num;$i++)
			{
				$safe_total=($total-($num-$i)*$min)/($num-$i);//随机安全上限
				$money=mt_rand($min*100,$safe_total*100)/100;
				$total=$total-$money;
				$moneys[] = $money*100;
			}
			$moneys[] = $total*100;
		}else{
			$avg = ($total/$num)*100;
			for($i=0;$i<$num;$i++){
				$moneys[] = $avg;
			}
		}
		return $moneys;
	}

	



	protected function addHelp($share_uid,$rid){
		global $_W,$_GPC;
		
		$uid=$_GPC['wxz_wzb_user'.$_W['uniacid']];
		if(!$uid || $uid==0){
			return ;
		}

		
		$log = pdo_fetch('SELECT * FROM ' . tablename('wxz_wzb_help') . ' WHERE `uniacid` = :uniacid AND `share_uid` = :share_uid AND `help_uid` = :help_uid AND `rid` = :rid', array(':uniacid' => $_W['uniacid'],':share_uid' => $share_uid,':help_uid' => $uid,':rid' => $rid) );

		if(!$log && $uid != $share_uid){
			$data['share_uid'] = $share_uid;
			$data['uniacid'] = $_W['uniacid'];
			$data['help_uid'] = $uid;
			$data['rid'] = $rid;
			$data['dateline'] = time();

			pdo_insert('wxz_wzb_help', $data);
		}
		
	}

	protected function addAmount($share_uid,$rid){
		global $_W,$_GPC;

		$uid=$_GPC['wxz_wzb_user'.$_W['uniacid']];
		if(!$uid || $uid==0){
			return ;
		}
		
		$help_user = pdo_fetch('SELECT * FROM ' . tablename('wxz_wzb_user') . ' WHERE `uniacid` = :uniacid AND `id` = :uid', array(':uniacid' => $_W['uniacid'],':uid' => $uid) );
		if(!$help_user){
		
			return ;
		}
		
		$user = pdo_fetch('SELECT * FROM ' . tablename('wxz_wzb_user') . ' WHERE `uniacid` = :uniacid AND `id` = :uid', array(':uniacid' => $_W['uniacid'],':uid' => $share_uid) );
		if(!$user){
			return ;
		}
		
		$viewer = pdo_fetch('SELECT * FROM ' . tablename('wxz_wzb_viewer') . ' WHERE `uid` = :uid AND `rid` = :rid', array(':uid' => $share_uid,':rid' => $rid) );

		
		$log = pdo_fetch('SELECT * FROM ' . tablename('wxz_wzb_share') . ' WHERE `uniacid` = :uniacid AND `share_uid` = :share_uid AND `help_uid` = :help_uid AND `rid` = :rid', array(':uniacid' => $_W['uniacid'],':share_uid' => $share_uid,':help_uid' => $uid,':rid' => $rid) );

		
		$setting = $this->getRedP($rid);
		$set = pdo_fetch("select * from ".tablename('wxz_wzb_setting')." where rid=".$rid." and uniacid = ".$_W['uniacid']);

		$auth = $this->ipAuth($set['getip_addr'],$set['getip'],$rid,$user['ip']);

		if(!$log && $uid != $share_uid && ($setting['pool_amount']-$setting['send_amount'])>0 && $auth){
			$data['share_uid'] = $share_uid;
			$data['uniacid'] = $_W['uniacid'];
			$data['help_uid'] = $uid;
			$data['rid'] = $rid;
			$data['dateline'] = time();
			
			if($setting['type']==2){
				
				$data['amount'] = rand($setting['reward_amount_min'],$setting['reward_amount_max']);
				
				if($data['amount']>($setting['pool_amount']-$setting['send_amount'])){
					$data['amount'] = $setting['pool_amount']-$setting['send_amount'];
				}

				pdo_update('wxz_wzb_viewer',array('amount'=>$viewer['amount']+$data['amount']),array('uid'=>$share_uid,'rid'=>$rid)); 
				
			}else{
				$data['amount'] = 0;
			}

			pdo_insert('wxz_wzb_share', $data);
			if($data['amount']>0){
				$id=pdo_insertid();
				$logdata=array(
					'uniacid'=>$_W['uniacid'],
					'uid'=>$data['share_uid'],
					'type'=>3,
					'amount'=>$data['amount'],
					'rid'=>$data['rid'],
					'fromid'=>$id,
					'fromuid'=>$data['help_uid'],
					'fromnickname'=>$help_user['nickname'],
					'fromheadimgurl'=>$help_user['headimgurl'],
					'dateline'=>time()
				);
				pdo_insert('wxz_wzb_money_log', $logdata);
			}
			pdo_update('wxz_wzb_live_red_packet',array('send_amount'=>$setting['send_amount']+$data['amount']),array('id' => $setting['id'])); 
		}
		
	}

	public function doMobileGetlivepic(){
		global $_W,$_GPC;
		
		$lastid = intval($_GPC['lastid']);
		$replyid = intval($_GPC['replyid']);
		$rid = intval($_GPC['rid']);
		$LivePic = pdo_fetchAll('SELECT * FROM ' . tablename('wxz_wzb_live_pic') .' where id>'.$lastid .' and rid='.$rid.' order by id desc');
		$setting = pdo_fetch('SELECT * FROM ' . tablename('wxz_wzb_setting') . ' WHERE `uniacid` = :uniacid and `rid` = :rid', array(':uniacid' => $_W['uniacid'],':rid' => $rid));
		$lhtml = '';
		foreach($LivePic as $key => $v){
			$lhtml .='<div class="detail">';
			$lhtml .='<div class="live_title">';
			$lhtml .='<h3>'.$v['title'].'</h3>';
			$lhtml .='<span class="time">'.date("H:i",$v['dateline']).'</span>';
			$lhtml .='<div class="clear"></div>';
			$lhtml .='</div>';
			$lhtml .='<figure>';
			$lhtml .='<figcaption>'.$v['content'].'</figcaption>';
			$lhtml .='</figure>';
			$lhtml .='</div> ';
		}
	
		$rhtml = '';
		$mrhtml = array();
		$mrhtml = array();
		
		$Comment = pdo_fetchAll('SELECT * FROM ' . tablename('wxz_wzb_comment') .' where id>'.$replyid .' and is_auth = 1 and rid ='.$rid.' order by id desc');

		foreach($Comment as $k => $val){
			if($val['toid']){
				if($val['ispacket']==1){
					$mrhtml[$val['toid']] .='<p style="font-size:12px; line-height:18px; font-weight:400; margin:10px; display:block; text-align:center;  border-radius:6px; background-color:#cecece; color:#FFFFFF">';
					$mrhtml[$val['toid']] .='<img  src="'.MODULE_URL.'template/mobile/img/mini_hongbao.png"  style=" width:14px; height:14px; vertical-align:middle; " /> ';
					$mrhtml[$val['toid']] .=$val['nickname'].'领取了<span style="color:#FF0000;">红包</span></p>';
				}else{
					$mrhtml[$val['toid']] .='<li  style="margin-top:10px;"><div class="marry-chat-content clearfix d-flex">';
					$mrhtml[$val['toid']] .='<img src="'.$val['headimgurl'].'" alt="" class="userphoto">';
					$mrhtml[$val['toid']] .='<div class="flex">';
					$mrhtml[$val['toid']] .='<span class="nickname">'.$val['nickname'].'</span>';
					$mrhtml[$val['toid']] .='<div class="msg-content">'.$val['content'].'</div>';
					$mrhtml[$val['toid']] .='</div>';
					$mrhtml[$val['toid']] .='</li>';
				}
				
			}else{
				
				if($val['ispacket']==1){	

					$rhtml .='<li class="d-flex">';
					$rhtml .='<div class="marry-chat-content clearfix d-flex">';
					$rhtml .='<img src="'.tomedia($val['headimgurl']).'" alt="" class="userphoto">';
					$rhtml .='<div class="flex">';
					$rhtml .='<span class="nickname">';
					$rhtml .=$val['nickname'].'</span>';
					$rhtml .='<div class="msg-content" style="background:none; padding:6px 6px 6px 0;">';
					$rhtml .='<a href="#"  onClick="$(\'.on_liaotian_hb\').show();$(\'#hb_money\').val('.$val['id'].');" style="display:block;"><img src="'.MODULE_URL.'template/mobile/img/hb_img_0001.png" width="100%" height="auto" alt="" /></a>';
					$rhtml .='<div id="hongbao_'.$val['id'].'">';
					$rhtml .='</div>';
					$rhtml .='<div class="nhzb-redline-all">';
					$rhtml .='<div class="nhzb-redline"></div>';
					$rhtml .='<div class="nhzb-hen"></div>';
					$rhtml .='</div>';
					$rhtml .='</div>';
					$rhtml .='</div>';
					$rhtml .='</div> '; 
					$rhtml .='</li>';	
	
					
					
				}else{
					
					if($val['dsid']>0 && $val['dsstatus']==1){
						$rhtml .='<li class="news-alert-time"><span>'.date("m-d H:i",$val['dateline']).'</span></li>';
						$rhtml .='<li class="d-flex">';
						$rhtml .='<div class="marry-chat-content clearfix d-flex" style="margin:0 auto;">';
						$rhtml .='<div class="flex">';
						$rhtml .='<div id="msg_ds" class="hongbao_lingqu">';
						$rhtml .='<p class="pmsg">'.$val['nickname'].'打赏了<span style="color:#FF0000;">'.($val['dsamount']/100).'</span>元';
						$rhtml .='</p>';
						$rhtml .='</div>';
						$rhtml .='</div>';
						$rhtml .='</div>';
						$rhtml .='</li>';
					}elseif($val['dsid']==0){
						
						$rhtml .='<li class="news-alert-time"><span>'.date("m-d H:i",$val['dateline']).'</span></li>';
						$rhtml .='<li class="d-flex" data-msgtype="1" attrtime="'.$val['dateline'].'" data-addtime="'.date('Y/m/d H:i',$val['dateline']).'" attr-uid="'.$val['uid'].'">';
						$rhtml .='<div class="marry-chat-content clearfix d-flex">';
						
						$rhtml .='<img src="'.$val['headimgurl'].'" alt="" class="userphoto">';
						$rhtml .='<div class="flex">';
						$rhtml .='<span class="nickname">';
						$rhtml .=$val['nickname'];
						$rhtml .='&nbsp;&nbsp;<a href="javascript:;" onclick="javascript:sendmsg('.$val['id'].',\''.$val['nickname'].'\');">回复</a></span>';
						$rhtml .='<div class="msg-content">';
						$rhtml .=$val['content'];
						$rhtml .='<div class="nhzb-redline-all">';
						$rhtml .='<div class="nhzb-redline"></div><div class="nhzb-hen"></div></div></div><ul id="msg_'.$val['id'].'"></ul></div></div></li>';
					}
					
				}
			}
			
		}

		$lasdCom =$Comment['0'];
		$lasdLive = $LivePic['0'];
		$result['status'] = 1;
		if(!$rhtml && !$mrhtml){
			$hfid = $replyid;
		}else{
			$hfid = empty($Comment) ? $replyid : $lasdCom['id'];
		}

		if(!$lhtml){
			$plid = $lastid;
		}else{
			$plid = empty($LivePic) ? $lastid : $lasdLive['id'];
		}
		$result['rhtml'] = $rhtml;
		$result['lhtml'] = $lhtml;
		$result['mrhtml'] = $mrhtml;
		$result['replyid'] = $hfid;
		$result['lastid'] = $plid;
		echo json_encode($result);
	}

	public function doMobileDs(){
		global $_W,$_GPC;
		$openid = $_W['openid'];
		$rid = intval($_GPC['rid']);
		$liuy = ($_GPC['liuy']);
		$uid=$_GPC['wxz_wzb_user'.$_W['uniacid']];
		load()->func('communication');
		if(empty($rid)){
			$result = array('s'=>'-1','msg'=>'直播不存在');
			echo json_encode($result);exit;
		}
		$data['listid'] = $listid;
		$data['categoryid'] = $category_id;
		
		$money = $_GPC['amount']*100;

		if(empty($money) || $money<1 ){
			$result = array('s'=>'-1','msg'=>'最少为0.01元哦！');
			echo json_encode($result);exit;
		}
		
		
		if(!$uid || $uid==0){
			$result = array('s'=>'-1','msg'=>'未知信息1！');
			echo json_encode($result);exit;
		}

		
		$user = pdo_fetch('SELECT * FROM ' . tablename('wxz_wzb_user') . ' WHERE `uniacid` = :uniacid AND `id` = :uid', array(':uniacid' => $_W['uniacid'],':uid' => $uid) );
		if(!$user){
			$result = array('s'=>'-1','msg'=>'未知信息2！');
			echo json_encode($result);exit;
		}

		$datads=array(
			'uniacid'=>$_W['uniacid'],
			'uid'=>$uid,
			'fee'=>$money,
			'rid'=>$rid,
			'dateline'=>time()
		);
		
		pdo_insert('wxz_wzb_ds', $datads);
		$id=pdo_insertid();
		$data=array(
			'uniacid'=>$_W['uniacid'],
			'uid'=>$uid,
			'ip'=>$_W['clientip'],
			'is_auth'=>1,
			'nickname'=>$user['nickname'],
			'headimgurl'=>$user['headimgurl'],
			'rid'=>$rid,
			'content'=>$_GPC['content'],
			'dsid'=>$id,
			'dsamount'=>$money,
			'dateline'=>time()
		);
		
		pdo_insert('wxz_wzb_comment', $data);
	

		$params = array(
			'fee' =>$money,
			'user' =>$_W['openid'],
			'title' =>urldecode($content),
			'random'=>random(16).$id,
		);

		$setting = uni_setting($_W['uniacid'], array('payment'));
		if($setting['payment']['wechat']['switch']==2){
			$setting = uni_setting($setting['payment']['wechat']['borrow'], array('payment'));
		}
		if(!is_array($setting['payment']['wechat'])) {
			pdo_delete('wxz_wzb_comment',array('id'=>$id));
			$result = array('s'=>'-1','msg'=>'信息出错！');
			echo json_encode($result);exit;
		}

		$wechat = $setting['payment']['wechat'];

		$sql = 'SELECT `key`,`secret` FROM ' . tablename('account_wechats') . ' WHERE `acid`=:acid';
		$row = pdo_fetch($sql, array(':acid' => $wechat['account']));
		$wechat['appid'] = $row['key'];
		$wechat['secret'] = $row['secret'];
		
		if ($wechat['version'] == 1) {
			$option = array();
			$option['appId'] = $wechat['appid'];
			$option['timeStamp'] = time();
			$option['nonceStr'] = random(8);
			$package = array();
			$package['bank_type'] = 'WX';
			$package['body'] = '打赏';
			$package['attach'] = $_W['uniacid'];
			$package['partner'] = $wechat['partner'];
			$package['out_trade_no'] = $params['random'];
			$package['total_fee'] = $params['fee'];
			$package['fee_type'] = '1';
			$package['notify_url'] = $_W['siteroot'] . 'addons/wxz_wzb/notify.php';
			$package['spbill_create_ip'] = CLIENT_IP;
			$package['time_start'] = date('YmdHis', time());
			$package['time_expire'] = date('YmdHis', time() + 600);
			$package['input_charset'] = 'UTF-8';
			ksort($package);
			$string1 = '';
			foreach($package as $key => $v) {
				if (empty($v)) {
					continue;
				}
				$string1 .= "{$key}={$v}&";
			}
			$string1 .= "key={$wechat['key']}";
			$sign = strtoupper(md5($string1));

			$string2 = '';
			foreach($package as $key => $v) {
				$v = urlencode($v);
				$string2 .= "{$key}={$v}&";
			}
			$string2 .= "sign={$sign}";
			$option['package'] = $string2;

			$string = '';
			$keys = array('appId', 'timeStamp', 'nonceStr', 'package', 'appKey');
			sort($keys);
			foreach($keys as $key) {
				$v = $option[$key];
				if($key == 'appKey') {
					$v = $wechat['signkey'];
				}
				$key = strtolower($key);
				$string .= "{$key}={$v}&";
			}
			$string = rtrim($string, '&');

			$option['signType'] = 'SHA1';
			$option['paySign'] = sha1($string);
			if (is_error($option)) {
				pdo_delete('wxz_wzb_comment',array('id'=>$id));
				$result = array('s'=>'-1','msg'=>'信息出错！');
				echo json_encode($result);exit;
			}
			$result = array('s'=>'-1','msg'=>$option);
			echo json_encode($result);exit;

		} else {

			$package = array();
			$package['appid'] = $wechat['appid'];
			$package['mch_id'] = $wechat['mchid'];
			$package['nonce_str'] = random(8);
			$package['body'] = '打赏';
			$package['attach'] = $_W['uniacid'];
			$package['out_trade_no'] = $params['random'];
			$package['total_fee'] = $params['fee'];
			$package['spbill_create_ip'] = CLIENT_IP;
			$package['time_start'] = date('YmdHis', time());
			$package['time_expire'] = date('YmdHis', time() + 600);
			$package['notify_url'] = $_W['siteroot'] . 'addons/wxz_wzb/notify.php';
			$package['trade_type'] = 'JSAPI';
			$package['openid'] = $user['openid'];

			ksort($package, SORT_STRING);
			$string1 = '';
			foreach($package as $key => $v) {
				if (empty($v)) {
					continue;
				}
				$string1 .= "{$key}={$v}&";
			}
			$string1 .= "key={$wechat['signkey']}";
			$package['sign'] = strtoupper(md5($string1));
			$dat = array2xml($package);
			$response = ihttp_request('https://api.mch.weixin.qq.com/pay/unifiedorder', $dat);
			
			if (is_error($response)) {
				$result = array('s'=>'-1','msg'=>strval($xml->return_msg));
			
				echo json_encode($result);exit;
				return $response;
			}
			
			$xml = @isimplexml_load_string($response['content'], 'SimpleXMLElement', LIBXML_NOCDATA);
					
			if (strval($xml->return_code) == 'FAIL') {
				$result = array('s'=>'-1','msg'=>strval($xml->return_msg));
			
				echo json_encode($result);exit;

			}

			if (strval($xml->result_code) == 'FAIL') {
				$result = array('s'=>'-1','msg'=>strval($xml->return_msg));
			
				echo json_encode($result);exit;

			}
			$prepayid = $xml->prepay_id;
			$option['appId'] = $wechat['appid'];
			$option['timeStamp'] = TIMESTAMP;
			$option['nonceStr'] = random(8);
			$option['package'] = 'prepay_id='.$prepayid;
			$option['signType'] = 'MD5';
			ksort($option, SORT_STRING);
			foreach($option as $key => $v) {
				$string .= "{$key}={$v}&";
			}
			$string .= "key={$wechat['signkey']}";
			$option['paySign'] = strtoupper(md5($string));
			$result = array('s'=>'1','msg'=>$option);
			
			echo json_encode($result);exit;
		}
		
	}

	

	public function doMobileSub(){
		global $_W,$_GPC;
		
		$uid=$_GPC['wxz_wzb_user'.$_W['uniacid']];
		$rid = intval($_GPC['rid']);
		$item = pdo_fetch('SELECT * FROM ' . tablename('wxz_wzb_live_setting') . ' WHERE `uniacid` = :uniacid and `rid` = :rid', array(':uniacid' => $_W['uniacid'],':rid' => $rid));
		if(!$uid){
			$msg = '请关注公众号！';
			echo json_encode($msg);
            exit;
        }

		$user = pdo_fetch('SELECT * FROM ' . tablename('wxz_wzb_user') . ' WHERE `uniacid` = :uniacid AND `id` = :uid', array(':uniacid' => $_W['uniacid'],':uid' => $uid) );

		$viewer = pdo_fetch('SELECT * FROM ' . tablename('wxz_wzb_viewer') . ' WHERE `uid` = :uid AND `rid` = :rid', array(':uid' => $uid,':rid' => $rid) );

		if($viewer['isshutup']==1){
			$msg = '您已被禁言！';
			echo json_encode($msg);
            exit;
		}

		if(!$user){
			$msg = '请关注公众号！';
			echo json_encode($msg);
            exit;
        }

		$touser = pdo_fetch('SELECT * FROM ' . tablename('wxz_wzb_comment') . ' WHERE `uniacid` = :uniacid AND `id` = :id and `rid` = :rid', array(':uniacid' => $_W['uniacid'],':id' => $_GPC['toid'],':rid' => $rid) );
		

		$data=array(
			'uniacid'=>$_W['uniacid'],
			'uid'=>$uid,
			'ip'=>$_W['clientip'],
			'is_auth'=>$item['is_auth']==1 ? 2 : 1,
			'nickname'=>$user['nickname'],
			'headimgurl'=>$user['headimgurl'],
			'rid'=>$rid,
			'content'=>$_GPC['content'],
			'toid'=>$_GPC['toid'],
			'touid'=>$touser['uid'],
			'tonickname'=>$touser['nickname'],
			'toheadimgurl'=>$touser['headimgurl'],
			'dateline'=>time()
		);
		
		pdo_insert('wxz_wzb_comment', $data);
		$id=pdo_insertid();
		$item = pdo_fetch('SELECT * FROM ' . tablename('wxz_wzb_live_setting') . ' WHERE `uniacid` = :uniacid and `rid` = :rid', array(':uniacid' => $_W['uniacid'],':rid' => $rid));
		
		if($id){
			if($item['is_auth'] == '1'){
				$msg = '提交成功，审核成功后显示';
			}else{
				$msg = '提交成功';
			}
			
		}else{
			$msg = '提交失败，请联系管理员';
		}
		echo json_encode($msg);
		exit;
	}

	

	public function doMobileShare(){
		global $_W,$_GPC;
		
		$uid=$_GPC['wxz_wzb_user'.$_W['uniacid']];
		$rid=$_GPC['rid'];
		if(!$rid){
			$result = array('type'=>'1','msg'=>'分享成功');echo json_encode($result);exit;
		}
		if(!$uid){
			$result = array('type'=>'-1','msg'=>'请关注');echo json_encode($result);exit;
		}

		$user = pdo_fetch('SELECT * FROM ' . tablename('wxz_wzb_user') . ' WHERE `uniacid` = :uniacid AND `id` = :uid', array(':uniacid' => $_W['uniacid'],':uid' => $uid) );

		$viewer = pdo_fetch('SELECT * FROM ' . tablename('wxz_wzb_viewer') . ' WHERE `uid` = :uid AND `rid` = :rid', array(':uid' => $uid,':rid' => $rid) );


		$setting = $this->getRedP($rid);
		$item = pdo_fetch("select * from ".tablename('wxz_wzb_live_setting')." where rid=".$rid." and uniacid = ".$_W['uniacid']);
		$set = pdo_fetch("select * from ".tablename('wxz_wzb_setting')." where rid=".$rid." and uniacid = ".$_W['uniacid']);
		if(!$user || $user['sub_openid'] ==""){
			$result = array('type'=>'-1','msg'=>'请关注');
		}
		$auth = $this->ipAuth($set['getip_addr'],$set['getip'],$rid,$user['ip']);
		if($item['reward']== 1 && $setting['type']== 1 && $viewer['share']==0 && ($setting['pool_amount']-$setting['send_amount'])>=100 && $auth){
			if($viewer['ispay']==0){
				$data=array(
					'share'=>'1',
					'amount'=>rand($setting['min'],$setting['max'])
				);
				if($data['amount']>($setting['pool_amount']-$setting['send_amount'])){
					$data['amount'] = $setting['pool_amount']-$setting['send_amount'];
				}
				pdo_update('wxz_wzb_live_red_packet',array('send_amount'=>$setting['send_amount']+$data['amount']),array('id' => $setting['id'])); 
				$res = pdo_update('wxz_wzb_viewer', $data, array('uid'=>$uid,'rid'=>$rid));
				$r = $this->Fee('1',$rid);
				if($r['type']== 1 ){
					$result = array('type'=>'1','msg'=>'分享成功');
				}else{
					$result = array('type'=>'-1','msg'=>$r['msg']);
				}
			}else{
				$result = array('type'=>'1','msg'=>'分享成功');
			}
		}else{
			$data=array(
				'share'=>'1'
			);
			$res = pdo_update('wxz_wzb_viewer', $data, array('uid'=>$uid,'rid'=>$rid));
			$result = array('type'=>'1','msg'=>'分享成功');
		}
		echo json_encode($result);exit;

	}

	public function sendReward($data){
		global $_W,$_GPC;
		$url                   = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
		$pars                  = array();
		$pars['nonce_str']     = random(32);
		$pars['mch_billno']    = $data['mchid'] . date('Ymd') . sprintf('%d', time());
		$pars['mch_id']        = $data['mchid'];
		$pars['wxappid']       = $data['appid'];
		$pars['nick_name']     = $_W['account']['name'];
		$pars['send_name']     = $data['send_name'];
		$pars['re_openid']     = $data['openid'];
		$pars['total_amount']  = $data['fee'];
		$pars['min_value']     = $data['fee'];
		$pars['max_value']     = $data['fee'];
		$pars['total_num']     = 1;
		$pars['wishing']       = $data['wishing'];
		$pars['client_ip']     = $data['ip'];
		$pars['act_name']      = $data['act_name'];
		$pars['remark']        = $data['remark'];
		$pars['logo_imgurl']   = tomedia($data['logo']);

		ksort($pars, SORT_STRING);
		$string1 = '';
		foreach ($pars as $k => $v) {
			$string1 .= "{$k}={$v}&";
		}
		$string1 .= "key={$data['password']}";

		$pars['sign']              = strtoupper(md5($string1));

		$xml                       = array2xml($pars);
		$extras                    = array();
		$extras['CURLOPT_CAINFO']  = MODULE_ROOT . '/cert/rootca.pem.' . $_W['uniacid'];
		$extras['CURLOPT_SSLCERT'] = MODULE_ROOT . '/cert/apiclient_cert.pem.' . $_W['uniacid'];
		$extras['CURLOPT_SSLKEY']  = MODULE_ROOT . '/cert/apiclient_key.pem.' . $_W['uniacid'];

		load()->func('communication');
		$resp = ihttp_request($url, $xml, $extras);
		if (is_error($resp)) {
			$procResult = $resp;
		} else {
			$arr=json_decode(json_encode((array) simplexml_load_string($resp['content'])), true);
			$xml = '<?xml version="1.0" encoding="utf-8"?>' . $resp['content'];
			$dom = new \DOMDocument();
			if ($dom->loadXML($xml)) {
				$xpath = new \DOMXPath($dom);
				$code = $xpath->evaluate('string(//xml/return_code)');
				$ret = $xpath->evaluate('string(//xml/result_code)');
				if (strtolower($code) == 'success' && strtolower($ret) == 'success') {
					$procResult =  array('errno'=>0,'error'=>'success');
				} else {
					$error = $xpath->evaluate('string(//xml/err_code_des)');
					$procResult = array('errno'=>-2,'error'=>$error);
				}
			} else {
				$procResult = array('errno'=>-1,'error'=>'未知错误');
			}
		}

		if ($procResult['errno']!=0) {
			$res = array('s'=>-1,'msg'=>$procResult['error']);
			return $res;
		}else{
			$res = array('s'=>1,'msg'=>'ok');
			return $res;
		}
		
	}

	public function radius_img($src_img = './t.png', $radius = 15) {
		$w  = 130;
		$h  = 130;
		// $radius = $radius == 0 ? (min($w, $h) / 2) : $radius;
		$img = imagecreatetruecolor($w, $h);
		//这一句一定要有
		imagesavealpha($img, true);
		//拾取一个完全透明的颜色,最后一个参数127为全透明
		$bg = imagecolorallocatealpha($img, 255, 255, 255, 127);
		imagefill($img, 0, 0, $bg);
		$r = $radius; //圆 角半径
		for ($x = 0; $x < $w; $x++) {
			for ($y = 0; $y < $h; $y++) {
				$rgbColor = imagecolorat($src_img, $x, $y);
				if (($x >= $radius && $x <= ($w - $radius)) || ($y >= $radius && $y <= ($h - $radius))) {
					//不在四角的范围内,直接画
					imagesetpixel($img, $x, $y, $rgbColor);
				} else {
					//在四角的范围内选择画
					//上左
					$y_x = $r; //圆心X坐标
					$y_y = $r; //圆心Y坐标
					if (((($x - $y_x) * ($x - $y_x) + ($y - $y_y) * ($y - $y_y)) <= ($r * $r))) {
						imagesetpixel($img, $x, $y, $rgbColor);
					}
					//上右
					$y_x = $w - $r; //圆心X坐标
					$y_y = $r; //圆心Y坐标
					if (((($x - $y_x) * ($x - $y_x) + ($y - $y_y) * ($y - $y_y)) <= ($r * $r))) {
						imagesetpixel($img, $x, $y, $rgbColor);
					}
					//下左
					$y_x = $r; //圆心X坐标
					$y_y = $h - $r; //圆心Y坐标
					if (((($x - $y_x) * ($x - $y_x) + ($y - $y_y) * ($y - $y_y)) <= ($r * $r))) {
						imagesetpixel($img, $x, $y, $rgbColor);
					}
					//下右
					$y_x = $w - $r; //圆心X坐标
					$y_y = $h - $r; //圆心Y坐标
					if (((($x - $y_x) * ($x - $y_x) + ($y - $y_y) * ($y - $y_y)) <= ($r * $r))) {
						imagesetpixel($img, $x, $y, $rgbColor);
					}
				}
			}
		}
		return $img;
	}

	public function Fee($type,$rid){
		global $_W,$_GPC;
		
		$uniacid = $_W['uniacid'];
		$uid=$_GPC['wxz_wzb_user'.$_W['uniacid']];
					
		$user = pdo_fetch('SELECT * FROM ' . tablename('wxz_wzb_user') . ' WHERE `uniacid` = :uniacid AND `id` = :uid', array(':uniacid' => $_W['uniacid'],':uid' => $uid) );

		$viewer = pdo_fetch('SELECT * FROM ' . tablename('wxz_wzb_viewer') . ' WHERE `uid` = :uid AND `rid` = :rid', array(':uid' => $uid,':rid' => $rid) );


		
		$api = $this->getRedP($rid);
		//先判断是否关注
		if($user['sub_openid']==''){
			$res['type']=-10;//未关注
			$res['msg']='请先关注在参加活动';//未关注
			//exit();
		}else{
			if($viewer['ispay']=='1' && $type!=2){
				$res['type']=-1;
				$res['msg']='您已提现';
				echo json_encode($res);
				exit();
			}
			if($viewer['share']=='0'){
				$res['type']=-1;
				$res['msg']='请先分享';
				echo json_encode($res);
				exit();
			}
			if($viewer['amount']>$api['pool_amount']){
				$res['type']=-1;
				$res['msg']='你的提现有点多';
				echo json_encode($res);
				exit();
			}
			$fee = $viewer['amount'] - $viewer['deposit'];
			if($fee<$api['withdraw_min']){
				if($api['type']==1 && $fee<100){
					$res = array('type'=>'1','msg'=>'分享成功');
				}else{
					$res['type']=-2;
					$res['msg']='您的提现金额少于'.($api['withdraw_min']/100).'元';
				}
				echo json_encode($res);exit;
			}
			
			$rec = array();
			$rec['uid'] = $user['id'];
			$rec['uniacid'] = $_W['uniacid'];
			$rec['fee'] = floatval($fee/100);
			$rec['dateline'] = TIMESTAMP;
			$rec['status'] = 'created';
			$rec['rid'] = $rid;
			$rec['type'] = $type;
			pdo_insert('wxz_wzb_packet_log', $rec);
			$logid=pdo_insertid();
			$actname = empty($api['actname']) ? '参与疯狂抢红包活动' : $api['actname'];

			if(empty($api['wishing'])){
				$wishing = '恭喜您,抽中了一个' . ($fee/100) . '元红包!';
			}else{
				$wishing = $api['wishing'] . ($fee/100) . '元红包!';
			}
			if(empty($api['sname'])){
				$send_name = $this->substr_cut($_W['account']['name'],30);
			}else{
				$send_name = $api['sname'];
			}
			$url                   = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
			$pars                  = array();
			$pars['nonce_str']     = random(32);
			$pars['mch_billno']    = $api['mchid'] . date('Ymd') . sprintf('%d', time());
			$pars['mch_id']        = $api['mchid'];
			$pars['wxappid']       = $api['appid'];
			$pars['nick_name']     = $_W['account']['name'];
			$pars['send_name']     = $send_name;
			$pars['re_openid']     = $user['openid'];
			$pars['total_amount']  = $fee;
			$pars['min_value']     = $pars['total_amount'];
			$pars['max_value']     = $pars['total_amount'];
			$pars['total_num']     = 1;
			$pars['wishing']       = $wishing;
			$pars['client_ip']     = $api['ip'];
			$pars['act_name']      = $actname;
			$pars['remark']        = '恭喜恭喜，您的' . ($fee/100) . '元红包已经发放，请注意查收';
			$pars['logo_imgurl']   = tomedia($api['logo']);
			
			ksort($pars, SORT_STRING);
			$string1 = '';
			foreach ($pars as $k => $v) {
				$string1 .= "{$k}={$v}&";
			}
			$string1 .= "key={$api['password']}";
			$pars['sign']              = strtoupper(md5($string1));
			$xml                       = array2xml($pars);
			$extras                    = array();
			$extras['CURLOPT_CAINFO']  = MODULE_ROOT . '/cert/rootca.pem.' . $uniacid;
			$extras['CURLOPT_SSLCERT'] = MODULE_ROOT . '/cert/apiclient_cert.pem.' . $uniacid;
			$extras['CURLOPT_SSLKEY']  = MODULE_ROOT . '/cert/apiclient_key.pem.' . $uniacid;

			load()->func('communication');
			$resp = ihttp_request($url, $xml, $extras);
			if (is_error($resp)) {
				$procResult = $resp;
			} else {
				$arr=json_decode(json_encode((array) simplexml_load_string($resp['content'])), true);
				$xml = '<?xml version="1.0" encoding="utf-8"?>' . $resp['content'];
				$dom = new \DOMDocument();
				if ($dom->loadXML($xml)) {
					$xpath = new \DOMXPath($dom);
					$code = $xpath->evaluate('string(//xml/return_code)');
					$ret = $xpath->evaluate('string(//xml/result_code)');
					if (strtolower($code) == 'success' && strtolower($ret) == 'success') {
						$procResult =  array('errno'=>0,'error'=>'success');
					} else {
						$error = $xpath->evaluate('string(//xml/err_code_des)');
						$procResult = array('errno'=>-2,'error'=>$error);
					}
				} else {
					$procResult = array('errno'=>-1,'error'=>'未知错误');
				}
			}

			if ($procResult['errno']!=0) {
				pdo_update('wxz_wzb_packet_log', array('status'=> $procResult['error']), array('id'=>$logid));
				$res['type']=-1;
				$res['msg']='提现失败';
				if($api['type']== 1){
					pdo_update('wxz_wzb_viewer', array('ispay'=>'2','rlog'=>$procResult['error']), array('uid'=>$uid,'rid'=>$rid));
				}else{
					pdo_update('wxz_wzb_viewer', array('rlog'=>$procResult['error']), array('uid'=>$uid,'rid'=>$rid));
				}
			}else{
				$res['type']=1;
				$res['msg']='提现成功';
				$res['id']=$logid;
				pdo_update('wxz_wzb_packet_log', array('status'=>'success'), array('id'=>$logid));
				if($api['type']== 1){
					$user_amount['ispay'] = 1;
					$user_amount['rlog'] = '发送成功';
					$user_amount['deposit'] = $fee+$viewer['deposit'];
					pdo_update('wxz_wzb_viewer', $user_amount, array('uid'=>$uid,'rid'=>$rid));
				}else{
					$user_amount['rlog'] = '发送成功';
					$user_amount['deposit'] = $fee+$viewer['deposit'];
					pdo_update('wxz_wzb_viewer', $user_amount, array('uid'=>$uid,'rid'=>$rid));
				}
				
			}
		}
		return $res;
	}

	//发送红包
	public function doMobileSend(){
        global $_W,$_GPC;
		
        $uniacid = $_W['uniacid'];
        $rid = $_GPC['rid'];
        $type = $_GPC['type'];
		$uid=$_GPC['wxz_wzb_user'.$_W['uniacid']];
		$user = pdo_fetch('SELECT * FROM ' . tablename('wxz_wzb_user') . ' WHERE `uniacid` = :uniacid AND `id` = :uid', array(':uniacid' => $_W['uniacid'],':uid' => $uid) );

		$viewer = pdo_fetch('SELECT * FROM ' . tablename('wxz_wzb_viewer') . ' WHERE `uid` = :uid AND `rid` = :rid', array(':uid' => $uid,':rid' => $rid) );
		
		if($viewer['ispay']==1 && $type !=2){
			$res['type']=1;//未关注
			$res['msg']='您已经提现过';//未关注
		}else{
			$res = $this->Fee('2',$rid);
		}
		echo json_encode($res);
    }

	public function doWebRedPacketSetting() {
		global $_W,$_GPC;

		$item = pdo_fetch("select * from ".tablename('wxz_wzb_red_packet')." where uniacid = ".$_W['uniacid']);
		
		if($_W['ispost']) {
            load()->func('file');
            mkdirs(MODULE_ROOT  . '/cert',0777);
            $r = true;
            if (!empty($_GPC['apiclient_cert'])) {
                $ret = file_put_contents(MODULE_ROOT  . '/cert/apiclient_cert.pem.' . $_W['uniacid'], trim($_GPC['apiclient_cert']));
                $r = $r && $ret;
            }
            if (!empty($_GPC['apiclient_key'])) {
                $ret = file_put_contents(MODULE_ROOT  . '/cert/apiclient_key.pem.' . $_W['uniacid'], trim($_GPC['apiclient_key']));
                $r = $r && $ret;
            }
            if (!empty($_GPC['rootca'])) {
                $ret = file_put_contents(MODULE_ROOT  . '/cert/rootca.pem.' . $_W['uniacid'], trim($_GPC['rootca']));
                $r = $r && $ret;
            }
            if (!$r) {
                message('证书保存失败');
            }

            $data = array();
            $data['password'] = trim($_GPC['password']);;
            $data['uniacid'] = $_W['uniacid'];
            $data['appid'] = trim($_GPC['appid']);
            $data['secret'] = trim($_GPC['secret']);
            $data['mchid'] = intval($_GPC['mchid']);
            $data['ip'] = $_GPC['ip'];
            $data['sname'] = trim($_GPC['sname']);
            $data['wishing'] = trim($_GPC['wishing']);
            $data['actname'] = trim($_GPC['actname']);
            $data['logo'] = trim($_GPC['logo']);
            $data['rootca'] = trim($_GPC['rootca']);
            $data['type'] = trim($_GPC['type']);
            $data['apiclient_key'] = trim($_GPC['apiclient_key']);
            $data['apiclient_cert'] = trim($_GPC['apiclient_cert']);
            $data['packet_rule'] = trim($_POST['packet_rule']);
            $data['createtime'] = time();

            if(empty($item)){
                pdo_insert('wxz_wzb_red_packet',$data);
            }else{
                pdo_update('wxz_wzb_red_packet',$data,array('uniacid' => $_W['uniacid']));
            }
            message('提交成功','','success');
        }
		if(empty($item)){
			$item['ip'] = $_W['clientip'];
		}

		include $this->template('redpacketsetting');
	}

	public function doWebIsauth(){
		global $_W,$_GPC;

		$id= $_GPC['id'];
		$rid= $_GPC['rid'];
		$single = pdo_fetch("select * from ".tablename('wxz_wzb_comment')." where id = ".$id);
		$is_auth= $_GPC['is_auth'];
		$r = array();
		if($is_auth == 1){
			$single['is_auth'] = $is_auth;
			unset($single['id']);
			pdo_insert('wxz_wzb_comment',$single);
			$viewer = pdo_fetch("select * from ".tablename('wxz_wzb_viewer')." where uid = ".$single['uid']." and rid =".$rid);
			$r['data']['isadmin'] = $viewer['role'] ==2 ?  1: 0;
			$r['data']['uid'] = intval($viewer['uid']);
			$r['data']['nickname'] = $single['nickname'];
			$r['data']['headimgurl'] = $single['headimgurl'];
			$r['data']['rid'] = intval($rid);
			$r['data']['shutup'] = intval($viewer['isshutup']);
			$r['data']['content'] = $single['content'];
			pdo_delete('wxz_wzb_comment', array('id' => $id));
			$r['auth'] = 1;
		}else{
			pdo_update('wxz_wzb_comment',array('is_auth'=>$is_auth),array('id' => $single['id']));
			$r['auth'] = 0;
		}
		$r['s'] = 1;
		echo json_encode($r);
        exit;
	}

	public function doWebIsshutup(){
		global $_W,$_GPC;
		
		$uid= $_GPC['uid'];
		$rid= $_GPC['rid'];
		$single = pdo_fetch("select * from ".tablename('wxz_wzb_viewer')." where uid = ".$uid);
		$isshutup= $_GPC['isshutup'];
		pdo_update('wxz_wzb_viewer',array('isshutup'=>$isshutup),array('uid' => $single['uid']));
		
		$return_url = $_W['siteroot'] .'web'.str_replace("./","/",$this->createWebUrl('comment',array('rid'=>$rid)));
        header("location: $return_url");
        exit;
	}

	public function doWebLiveList(){
		global $_W,$_GPC;
		
		$pindex = max(1, intval($_GPC['page']));
		$psize = 10;
		$total = pdo_fetchcolumn("SELECT count(*) FROM ".tablename('wxz_wzb_live_setting')." where `uniacid`=:uniacid",array(':uniacid'=>$_W['uniacid']));
		$start = ($pindex - 1) * $psize;

		$sql='SELECT * FROM ' . tablename('wxz_wzb_live_setting') .' WHERE uniacid = '.$_W['uniacid'].' and islinkurl=0 order by id desc limit '.$start.','.$psize;
		
		$list = pdo_fetchall($sql);
		$pager = pagination($total, $pindex, $psize);
		include $this->template('live_list');
	}

	public function doWebWithdraw(){
		global $_W,$_GPC;
		
		$rid = intval($_GPC['rid']);
		$uid = $_GPC['uid'];
		$user = pdo_fetch('SELECT * FROM ' . tablename('wxz_wzb_user') . ' WHERE `uniacid` = :uniacid AND `id` = :uid', array(':uniacid' => $_W['uniacid'],':uid' => $uid) );

		$sql='SELECT b.nickname,b.id,b.headimgurl,a.fee,a.dateline,a.status FROM ' . tablename('wxz_wzb_packet_log') . ' as a inner JOIN ' . tablename('wxz_wzb_user') . ' AS b ON a.uid=b.id inner join ' . tablename('wxz_wzb_viewer') . ' as c on b.id=c.uid WHERE a.uniacid = '.$_W['uniacid'].' and a.uid='.$uid.' and c.rid='.$rid.' and a.status!="created" order by id desc';

		$list = pdo_fetchall($sql);

		include $this->template('withdraw');
	}

	public function doWebDel(){
		global $_W,$_GPC;
		
		$id = intval($_GPC['id']);
		pdo_delete('wxz_wzb_live_setting', array('id' => $id));
		message('删除成功', referer(),'success');
	}

	public function doWebDelpic(){
		global $_W,$_GPC;
		
		$id = intval($_GPC['id']);
		pdo_delete('wxz_wzb_live_pic', array('id' => $id));
		message('删除成功', referer(),'success');
	}

	public function doWebSendGroupPacket(){
		global $_W,$_GPC;
		
		$rid = intval($_GPC['rid']);
		if(isset($_GPC['item']) && $_GPC['item'] == 'ajax' && $_GPC['key'] == 'setting'){
			
			$moneys = $this->randBonus($_GPC['amount']/100,$_GPC['num'],$_GPC['type']);
			$data=array(
				'uniacid'=>$_W['uniacid'],
				'uid'=>0,
				'rid'=>$_GPC['rid'],
				'type'=>$_GPC['type'],
				'amount'=>$_GPC['amount'],
				'num'=>$_GPC['num'],
				'status'=>1,
				'remark'=>$remark,
				'json'=>iserializer($moneys),
				'dateline'=>time()
			);
			pdo_insert('wxz_wzb_grouppacket', $data);
			$gid=pdo_insertid();

            $data=array(
                'uniacid'=>$_W['uniacid'],
                'content'=>$_POST['content'],
				'isadmin'=>1,
				'is_auth'=>1,
				'type'=>$_GPC['type'],
				'headimgurl'=>tomedia($_GPC['headimgurl']),
				'nickname'=>$_GPC['nickname'],
				'num'=>$_GPC['num'],
				'amount'=>$_GPC['amount'],
				'ispacket'=>1,
				'rid'=>$_GPC['rid'],
				'gid'=>$gid,
				'samount'=>iserializer($moneys),
				'dateline'=>time()
            );
            pdo_insert('wxz_wzb_comment', $data);
			$cid=pdo_insertid();
			$data['id'] = $cid;
			$data['dateline'] = date('Y-m-d H:i:s',$data['dateline']);
			echo json_encode(array('s'=>1,'data'=>$data));
			exit;
		}

        load()->func('tpl');
		include $this->template('sendGroupPacket');
	}

	public function doWebLivePic(){
		global $_W,$_GPC;
		
		$rid = intval($_GPC['rid']);
		$id = intval($_GPC['id']);
		$livesetting = pdo_fetch("select * from ".tablename('wxz_wzb_live_setting')." where rid=".$rid." and uniacid = ".$_W['uniacid']);
		if(isset($_GPC['item']) && $_GPC['item'] == 'ajax' && $_GPC['key'] == 'setting'){
            $data=array(
                'uniacid'=>$_W['uniacid'],
                'content'=>$_POST['content'],
                'publisher'=>$livesetting['publisher'],
                'images'=>tomedia($livesetting['images']),
                'title'=>$_GPC['title'],
				'rid'=>$_GPC['rid'],
				'dateline'=>time()
            );
            pdo_insert('wxz_wzb_live_pic', $data);
			$id=pdo_insertid();
			$pollingData = array(
				'rid'=>$rid,
				'type'=>8,
				'pic_id'=>$id,
			);
			pdo_insert('wxz_wzb_polling', $pollingData);
		}
		if($id){
			$item = pdo_fetch('SELECT * FROM ' . tablename('wxz_wzb_live_pic') . ' WHERE `uniacid` = :uniacid and id=:id', array(':uniacid' => $_W['uniacid'],':id' => $id));
		}
		

		$pindex = max(1, intval($_GPC['page']));
		$psize = 10;

		$total = pdo_fetchcolumn("SELECT count(*) FROM ".tablename('wxz_wzb_live_pic')." where `uniacid`=:uniacid and rid=:rid",array(':uniacid'=>$_W['uniacid'],':rid' => $rid));
		$start = ($pindex - 1) * $psize;

		$sql='SELECT * FROM ' . tablename('wxz_wzb_live_pic') .' WHERE uniacid = '.$_W['uniacid'].' and rid='.$rid.' order by id desc limit '.$start.','.$psize;
		
		$LivePic = pdo_fetchall($sql);
		$pager = pagination($total, $pindex, $psize);

        load()->func('tpl');
		include $this->template('live_pic');
	}

	public function doWebComment(){
		global $_W, $_GPC;
		
		$rid = intval($_GPC['rid']);
        $pindex = max(1, intval($_GPC['page']));
		$psize = 30;
		$total = pdo_fetchAll("SELECT count(*) FROM ".tablename('wxz_wzb_comment')." as a left join " . tablename('wxz_wzb_viewer') ." as b on a.uid=b.uid where a.uniacid=:uniacid and a.rid=:rid and (a.ispacket!=1 or a.isadmin=1) and (a.dsid=0 or (a.dsid>0 and a.dsstatus=1)) GROUP BY a.id ",array(':uniacid'=>$_W['uniacid'],':rid' => $rid));
		$total = count($total);
		$start = ($pindex - 1) * $psize;

		$sql='SELECT a.*,b.isshutup as isshutup FROM ' . tablename('wxz_wzb_comment') .' as a left join ' . tablename('wxz_wzb_viewer') .' as b on a.uid=b.uid WHERE a.uniacid = '.$_W['uniacid'].' and a.rid='.$rid.'  and (a.ispacket!=1 or a.isadmin=1) and (a.dsid=0 or (a.dsid>0 and a.dsstatus=1)) GROUP BY a.id order by a.id desc limit '.$start.','.$psize;

		$Comment = pdo_fetchall($sql);

		$pager = pagination($total, $pindex, $psize);

		include $this->template('comment');
	}

	public function doWebGetpacket(){
		global $_W, $_GPC;
		
		$rid = intval($_GPC['rid']);
		$toid = intval($_GPC['toid']);
        $pindex = max(1, intval($_GPC['page']));
		$psize = 10;
		$total = pdo_fetchcolumn("SELECT count(*) FROM ".tablename('wxz_wzb_comment')." where `uniacid`=:uniacid and rid=:rid and toid=:toid",array(':uniacid'=>$_W['uniacid'],':rid' => $rid,':toid' => $toid));
		$start = ($pindex - 1) * $psize;

		$sql='SELECT * FROM ' . tablename('wxz_wzb_comment') .' WHERE uniacid = '.$_W['uniacid'].' and rid='.$rid.' and toid='.$toid.' order by id desc limit '.$start.','.$psize;
		
		$Comment = pdo_fetchall($sql);
		$pager = pagination($total, $pindex, $psize);

		include $this->template('getpacket');
	}

	public function doWebUsers(){
		global $_W, $_GPC;
		$rid = intval($_GPC['rid']);
		$type = ($_GPC['type']);
        $pindex = max(1, intval($_GPC['page']));
		$psize = 10;
		$condition = ' where 1=1';
		if($_GPC['nickname']){
			$condition .= "  and b.nickname like '%".$_GPC['nickname']."%'";
		}
		if($_GPC['openid']){
			$condition .= "  and b.openid like '%".$_GPC['openid']."%'";
		}

		if($type=='all'){
			$psize = 100000000;
			$start = ($pindex - 1) * $psize;

			$sql='SELECT b.id as id,a.rid as rid,a.id as uid,b.nickname as nickname,b.sub_openid as sub_openid,b.openid as openid,b.headimgurl as headimgurl,a.amount as amount,a.deposit as deposit,a.dateline as dateline FROM ' . tablename('wxz_wzb_viewer').' as a inner JOIN ' . tablename('wxz_wzb_user') . ' AS b ON a.uid=b.id '.$condition.' and b.`uniacid`='.$_W['uniacid'].' and a.rid='.$rid.' order by b.id desc';
			
			$Users = pdo_fetchall($sql);
			$pager = pagination($total, $pindex, $psize);
		}else{
			$total = pdo_fetchcolumn("SELECT count(*) FROM ".tablename('wxz_wzb_viewer')." as a inner JOIN " . tablename('wxz_wzb_user') . " AS b ON a.uid=b.id ".$condition." and b.`uniacid`=:uniacid and a.rid=:rid",array(':uniacid'=>$_W['uniacid'],':rid' => $rid));
			$start = ($pindex - 1) * $psize;

			$sql='SELECT b.id as id,a.rid as rid,a.id as uid,b.nickname as nickname,b.sub_openid as sub_openid,b.openid as openid,b.headimgurl as headimgurl,a.amount as amount,a.deposit as deposit,a.dateline as dateline FROM ' . tablename('wxz_wzb_viewer').' as a inner JOIN ' . tablename('wxz_wzb_user') . ' AS b ON a.uid=b.id '.$condition.' and b.`uniacid`='.$_W['uniacid'].' and a.rid='.$rid.' order by b.id desc limit '.$start.','.$psize;
			
			$Users = pdo_fetchall($sql);
			$pager = pagination($total, $pindex, $psize);
		}

		

		include $this->template('users');
	}
	
	public function doWebHelp(){
		global $_W,$_GPC;
		
		$rid = intval($_GPC['rid']);
		$uid = $_GPC['uid'];
		$user = pdo_fetch('SELECT * FROM ' . tablename('wxz_wzb_user') . ' WHERE `uniacid` = :uniacid AND `id` = :uid', array(':uniacid' => $_W['uniacid'],':uid' => $uid) );
		
		$viewer = pdo_fetch('SELECT * FROM ' . tablename('wxz_wzb_viewer') . ' WHERE `uid` = :uid AND `rid` = :rid', array(':uid' => $uid,':rid' => $rid) );

		$sql='SELECT b.nickname,b.id,b.headimgurl,a.amount,a.dateline FROM ' . tablename('wxz_wzb_share') . ' as a LEFT JOIN ' . tablename('wxz_wzb_user') . ' AS b ON a.help_uid=b.id inner join '.tablename('wxz_wzb_viewer').' as c on b.id=c.uid WHERE a.uniacid = '.$_W['uniacid'].' and a.share_uid='.$uid.' and c.rid='.$rid.' order by id desc';
		$help_user = pdo_fetchall($sql);

		include $this->template('help');
	}

	public function checkMobile(){
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		if (strpos($user_agent, 'MicroMessenger') === false) {
			echo "HTTP/1.1 401 Unauthorized";exit;
		}
	}

	/**
         * 商城公共函数start
         */
        protected function checkAuth() {
		global $_W;
		$setting = cache_load('unisetting:'.$_W['uniacid']);
		if (empty($_W['member']['uid']) && empty($setting['passport']['focusreg'])) {
			$fan = pdo_get('mc_mapping_fans', array('uniacid' =>$_W['uniacid'], 'openid' => $_W['openid']));
			if (!empty($fan)) {
				$fanid = $fan['fanid'];
			} else {
				if (empty($_W['openid'])) {
					$_W['opendi'] = random(28);
				}
				$post = array(
					'uniacid' => $_W['uniacid'],
					'updatetime' => time(),
					'openid' => $_W['openid'],
					'follow' => 0,
				);
				pdo_insert('mc_mapping_fans', $post);
				$fanid =  pdo_insertid();
			}
			if (empty($fan['uid'])) {
				pdo_insert('mc_members', array('uniacid' => $_W['uniacid']));
				$uid = pdo_insertid();
				$_W['member']['uid'] = $uid;
				$_W['fans']['uid'] = $uid;
				pdo_update('mc_mapping_fans', array('uid' => $uid), array('fanid' => $fanid));
			} else {
				$_W['member']['uid'] = $fan['uid'];
				$_W['fans']['uid'] = $fan['uid'];
			}
		} else {
			checkauth();
		}
	}
        //支付回调
        public function payWxResult($params) {
		global $_W;
		$fee = intval($params['fee']);
		$data = array('pay_status' => $params['result'] == 'success' ? 2 : 0);
		$paytype = array('credit' => '1', 'wechat' => '2', 'alipay' => '2', 'delivery' => '3');

		// 卡券代金券备注
//		if (!empty($params['is_usecard'])) {
//			$cardType = array('1' => '微信卡券', '2' => '系统代金券');
//			$data['paydetail'] = '使用' . $cardType[$params['card_type']] . '支付了' . ($params['fee'] - $params['card_fee']);
//			$data['paydetail'] .= '元，实际支付了' . $params['card_fee'] . '元。';
//		}
		$data['paytype'] = $paytype[$params['type']];
                $data['pay_name'] = $params['type'];
		if ($paytype[$params['type']] == '') {
			$data['paytype'] = 4;
		}
		if ($params['type'] == 'wechat') {
			$data['transid'] = $params['tag']['transaction_id'];
		}
		if ($params['type'] == 'delivery') {
			$data['pay_status'] = 0;
		}

		if ($_SESSION['ewei_shopping_pay_result'] != $params['tid']) {
			session_start();
			$_SESSION['ewei_shopping_pay_result'] = $params['tid'];
                        $data['order_status'] = 1;
			pdo_update('wxz_lzl_order_info', $data, array('order_id' => $params['tid'], 'weid' => $_W['uniacid']));
		}else {
			$setting = uni_setting($_W['uniacid'], array('creditbehaviors'));
			$credit = $setting['creditbehaviors']['currency'];
			if ($params['type'] == $credit) {
				message('支付成功！', $this->createMobileUrl('myorder', array('status' => 2)), 'success');
			} else {
				message('支付成功！', '../../app/' . $this->createMobileUrl('myorder', array('status' => 2)), 'success');
			}
		}
		if ($params['from'] == 'return') {
			//积分变更
			//$this->setOrderCredit($params['tid']);

			if (!empty($this->module['config']['noticeemail']) || !empty($this->module['config']['template'])|| !empty($this->module['config']['mobile'])) {
				$order = pdo_fetch("SELECT `order_sn`, `goods_amount`, `shipping_fee`, `paytype`, `from_user`, `address`, `add_time` FROM " . tablename('wxz_lzl_order_info') . " WHERE order_id = '{$params['tid']}'");
				$ordergoods = pdo_fetchall("SELECT goods_id,goods_name,goods_number FROM " . tablename('wxz_lzl_order_goods') . " WHERE order_id = '{$params['tid']}'", array());

				$address = explode('|', $order['address']);

				// 邮件提醒
				if (!empty($this->module['config']['noticeemail'])) {
					$body = "<h3>购买商品清单</h3> <br />";
					if (!empty($ordergoods)) {
						foreach ($ordergoods as $val) {
							$body .= "名称：{$val['goods_name']} ，数量：{$val['goods_number']} <br />";
						}
					}
					$paytype = $order['paytype'] == '3' ? '货到付款' : '已付款' . '<br />';
					$body .= '总金额：' . $order['price'] . '元' . $paytype . '<br />';
					$body .= '<h3>购买用户详情</h3> <br />';
					$body .= '真实姓名：' . $address[0] . '<br />';
					$body .= '地区：' . $address[3] . ' - ' . $address[4] . ' - ' . $address[5] . '<br />';
					$body .= '详细地址：' . $address[6] . '<br />';
					$body .= '手机：' . $address[1]  . '<br />';

					load()->func('communication');
					ihttp_email($this->module['config']['noticeemail'], '微商城订单提醒', $body);
				}
				//模板消息
				if (!empty($this->module['config']['template'])) {
					$good = '';
					$address = explode('|', $order['address']);
					if (!empty($ordergoods)) {
						foreach ($ordergoods as $val) {
							$good .= "\n"."名称：{$val['goods_name']} ，数量：{$val['goods_number']} ";
						}
					}
					$paytype = $order['paytype'] == '3' ? '货到付款' : '已付款';
					$data = array (
						'first' => array('value' => '购买商品清单'),
						'keyword1' => array('value' => date('Y-m-d H:i',strtotime('now'))),
						'keyword2' => array('value' => "\n".$good),
						'keyword3' => array('value' => $order['price']),
						'keyword4' => array('value' => "\n".'真实姓名：' . $address[0]."\n".'地区：' . $address[3] . ' - ' . $address[4] . ' - ' . $address[5]."\n".'详细地址：' . $address[6] ."\n".'手机：' . $address[1]),
						'keyword5' => array('value' => $paytype)
					);
					$acc = WeAccount::create($_W['acid']);
					$acc->sendTplNotice($_W['fans']['from_user'],$this->module['config']['templateid'],$data);
				}
				// 短信提醒
				if (!empty($this->module['config']['mobile'])) {
					load()->model('cloud');
					cloud_prepare();
					cloud_sms_send($this->module['config']['mobile'], '800001', array('user' => $address[0], 'mobile' => $address[1], 'datetime' => date('m月d日H:i'), 'order_no' => $order['order_sn'], 'totle' => $order['goods_amount']+$order['shipping_fee']));
				}
			}

			$setting = uni_setting($_W['uniacid'], array('creditbehaviors'));
			$credit = $setting['creditbehaviors']['currency'];
			if ($params['type'] == $credit) {
				message('支付成功！', $this->createMobileUrl('myorder'), 'success');
			} else {
				message('支付成功！', '../../app/' . $this->createMobileUrl('myorder'), 'success');
			}
		}
	}
        /**
         * 商品最终价格
         * @param type $goods_id
         * @param type $goods_num
         * @param type $is_spec_price
         * @param type $spec
         * @return type
         */
        function get_final_price($goods_id, $goods_num = '1', $is_spec_price = false, $spec = array())
        {
            $final_price   = '0'; //商品最终购买价格
            $promote_price = '0'; //商品促销价格
            $user_price    = '0'; //商品会员价格

            //取得商品促销价格列表
            /* 取得商品信息 */
            $sql = "SELECT g.promote_price, g.promote_start_date, g.promote_end_date, is_promote, ".
                        "IFNULL(mp.user_price, g.shop_price) AS shop_price ".
                   " FROM " .tablename('wxz_lzl_goods'). " AS g ".
                   " LEFT JOIN " . tablename('wxz_lzl_member_price') . " AS mp ".
                           "ON mp.goods_id = g.goods_id ".
                   " WHERE g.goods_id = '" . $goods_id . "'" .
                   " AND g.is_delete = 0";
            $goods = pdo_fetch($sql);
            /* 计算商品的促销价格 */
            if ($goods['promote_price'] > 0 && $goods['is_promote'] == 1)
            {
                $promote_price = $this->bargain_price($goods['promote_price'], $goods['promote_start_date'], $goods['promote_end_date']);
            }
            else
            {
                $promote_price = 0;
            }

            //取得商品会员价格列表
            $user_price    = $goods['shop_price'];

            if (!empty($promote_price))
            {
                //如果促销价格为空时不参加这个比较。
                $final_price = min($promote_price, $user_price);
            }
            else
            {
                $final_price = $user_price;
            }
            
            //如果需要加入规格价格
            if ($is_spec_price)
            {
                if (!empty($spec))
                {
                    $spec_price   = $this->spec_price($spec);
                    $final_price += $spec_price;
                }
            }

            //返回商品最终购买价格
            return $final_price;
        }
        /**
        * 获得指定的规格的价格
        *
        * @access  public
        * @param   mix     $spec   规格ID的数组或者逗号分隔的字符串
        * @return  void
        */
        public function spec_price($spec) {
           if (!empty($spec))
           {
               $where = $this->db_create_in($spec, 'goods_attr_id');

               $sql = 'SELECT SUM(attr_price) AS attr_price FROM ' . tablename('wxz_lzl_goods_attr') . " WHERE $where";
               $row = pdo_fetch($sql);
               $price = floatval($row['attr_price']);
           }
           else
           {
               $price = 0;
           }

           return $price;
        }
        /**
	 * 获得商品的属性和规格
	 *
	 * @access  public
	 * @param   integer $goods_id
	 * @return  array
	 */
	function get_goods_properties($goods_id)
	{
		/* 对属性进行重新排序和分组 */
		$sql = "SELECT attr_group ".
				"FROM " . tablename('wxz_lzl_goods_type') . " AS gt, " . tablename('wxz_lzl_goods') . " AS g ".
				"WHERE g.goods_id='$goods_id' AND gt.cat_id=g.goods_type";
		$grp = pdo_fetchcolumn($sql);
		
		if (!empty($grp))
		{
			$groups = explode("\n", strtr($grp, "\r", ''));
		}

		/* 获得商品的规格 */
		$sql = "SELECT a.attr_id, a.attr_name, a.attr_group, a.is_linked, a.attr_type, ".
					"g.goods_attr_id, g.attr_value, g.attr_price " .
				'FROM ' . tablename('wxz_lzl_goods_attr') . ' AS g ' .
				'LEFT JOIN ' . tablename('wxz_lzl_attribute') . ' AS a ON a.attr_id = g.attr_id ' .
				"WHERE g.goods_id = '$goods_id' " .
				'ORDER BY a.sort_order, g.attr_price, g.goods_attr_id';
		$res = pdo_fetchall($sql);

		$arr['pro'] = array();     // 属性
		$arr['spe'] = array();     // 规格
		$arr['lnk'] = array();     // 关联的属性

		foreach ($res AS $row)
		{
			$row['attr_value'] = str_replace("\n", '<br />', $row['attr_value']);

			if ($row['attr_type'] == 0)
			{
				$group = (isset($groups[$row['attr_group']])) ? $groups[$row['attr_group']] : $GLOBALS['_LANG']['goods_attr'];

				$arr['pro'][$group][$row['attr_id']]['name']  = $row['attr_name'];
				$arr['pro'][$group][$row['attr_id']]['value'] = $row['attr_value'];
			}
			else
			{
				$arr['spe'][$row['attr_id']]['attr_type'] = $row['attr_type'];
				$arr['spe'][$row['attr_id']]['name']     = $row['attr_name'];
				$arr['spe'][$row['attr_id']]['values'][] = array(
															'label'        => $row['attr_value'],
															'price'        => $row['attr_price'],
															'format_price' => $this->price_format(abs($row['attr_price']), false),
															'id'           => $row['goods_attr_id']);
			}

			if ($row['is_linked'] == 1)
			{
				/* 如果该属性需要关联，先保存下来 */
				$arr['lnk'][$row['attr_id']]['name']  = $row['attr_name'];
				$arr['lnk'][$row['attr_id']]['value'] = $row['attr_value'];
			}
		}

		return $arr;
	}
        /**
	 * 创建像这样的查询: "IN('a','b')";
	 *
	 * @access   public
	 * @param    mix      $item_list      列表数组或字符串
	 * @param    string   $field_name     字段名称
	 *
	 * @return   void
	 */
	function db_create_in($item_list, $field_name = '')
	{
		if (empty($item_list))
		{
			return $field_name . " IN ('') ";
		}
		else
		{
			if (!is_array($item_list))
			{
				$item_list = explode(',', $item_list);
			}
			$item_list = array_unique($item_list);
			$item_list_tmp = '';
			foreach ($item_list AS $item)
			{
				if ($item !== '')
				{
					$item_list_tmp .= $item_list_tmp ? ",'$item'" : "'$item'";
				}
			}
			if (empty($item_list_tmp))
			{
				return $field_name . " IN ('') ";
			}
			else
			{
				return $field_name . ' IN (' . $item_list_tmp . ') ';
			}
		}
	}
        /**
	 * 将 goods_attr_id 的序列按照 attr_id 重新排序
	 *
	 * 注意：非规格属性的id会被排除
	 *
	 * @access      public
	 * @param       array       $goods_attr_id_array        一维数组
	 * @param       string      $sort                       序号：asc|desc，默认为：asc
	 *
	 * @return      string
	 */
	function sort_goods_attr_id_array($goods_attr_id_array, $sort = 'asc')
	{
		if (empty($goods_attr_id_array))
		{
			return $goods_attr_id_array;
		}

		//重新排序
		$sql = "SELECT a.attr_type, v.attr_value, v.goods_attr_id
				FROM " .tablename('wxz_lzl_attribute'). " AS a
				LEFT JOIN " .tablename('wxz_lzl_goods_attr'). " AS v
					ON v.attr_id = a.attr_id
					AND a.attr_type = 1
				WHERE v.goods_attr_id " . $this->db_create_in($goods_attr_id_array) . "
				ORDER BY a.attr_id $sort";
		$row = pdo_fetchall($sql);

		$return_arr = array();
		foreach ($row as $value)
		{
			$return_arr['sort'][]   = $value['goods_attr_id'];

			$return_arr['row'][$value['goods_attr_id']]    = $value;
		}

		return $return_arr;
	}
        /**
	 * 格式化商品价格
	 *
	 * @access  public
	 * @param   float   $price  商品价格
	 * @return  string
	 */
	function price_format($price, $change_price = true)
	{
		if($price==='')
		{
		 $price=0;
		}
		if ($change_price)
		{
			switch (0)
			{
				case 0:
					$price = number_format($price, 2, '.', '');
					break;
				case 1: // 保留不为 0 的尾数
					$price = preg_replace('/(.*)(\\.)([0-9]*?)0+$/', '\1\2\3', number_format($price, 2, '.', ''));

					if (substr($price, -1) == '.')
					{
						$price = substr($price, 0, -1);
					}
					break;
				case 2: // 不四舍五入，保留1位
					$price = substr(number_format($price, 2, '.', ''), 0, -1);
					break;
				case 3: // 直接取整
					$price = intval($price);
					break;
				case 4: // 四舍五入，保留 1 位
					$price = number_format($price, 1, '.', '');
					break;
				case 5: // 先四舍五入，不保留小数
					$price = round($price);
					break;
			}
		}
		else
		{
			$price = number_format(floatval($price), 2, '.', '');
		}

		return sprintf('¥%s', $price);
	}
        /**
        * 添加商品到购物车
        *
        * @access  public
        * @param   integer $goods_id   商品编号
        * @param   integer $num        商品数量
        * @param   array   $spec       规格值对应的id数组
		* @param   integer $num        首页商品数量即购物车数量
        * @return  boolean
        */
       function addto_cart($goods_id, $num = 1, $spec = array(), $is_direct=0)
       {
            global $_W, $_GPC;
           $data = array('msg' => '', 'err' => 0);
           /* 取得商品信息 */
           $sql = "SELECT * FROM " .tablename('wxz_lzl_goods'). " WHERE goods_id = '$goods_id'" .
                   " AND is_delete = 0";
           $goods = pdo_fetch($sql);

           /* 是否正在销售 */
           if ($goods['is_on_sale'] == 0)
           {
               $data['err'] = 1;
               $data['msg'] = '该商品已下架！';
			   return $data;
           }

           /* 如果商品有规格则取规格商品信息 配件除外 */
           $sql = "SELECT * FROM " .tablename('wxz_lzl_products'). " WHERE goods_id = '$goods_id' LIMIT 0, 1";
           $prod = pdo_fetch($sql);

           if ($this->is_spec($spec) && !empty($prod))
           {
               $product_info = $this->get_products_info($goods_id, $spec);
           }
           if (empty($product_info))
           {
               $product_info = array('product_number' => '', 'product_id' => 0);
           }

           /* 检查：库存 */
            //检查：商品购买数量是否大于总库存
            if ($num > $goods['goods_number'])
            {
               $data['err'] = 1;
               $data['msg'] = '该商品库存不足！';
			   return $data;
            }
            //商品存在规格 是货品 检查该货品库存
            if ($this->is_spec($spec) && !empty($prod))
            {
                if (!empty($spec))
                {
                    /* 取规格的货品库存 */
                    if ($num > $product_info['product_number'])
                    {
                        $data['err'] = 1;
                        $data['msg'] = '该商品库存不足！';
						return $data;
                    }
                }
            }       
			

           /* 计算商品的促销价格 */
           $spec_price             = $this->spec_price($spec);
           $goods_price            = $this->get_final_price($goods_id, $num, true, $spec);
           $goods['market_price'] += $spec_price;
           $goods_attr             = $this->get_goods_attr_info($spec);
           $goods_attr_id          = join('|', $spec);
			
           /* 初始化要插入购物车的基本件数据 */
           $param = array(
               'from_user'     => $_W['fans']['from_user'],
               'weid' 		   => $_W['uniacid'],
               'goods_id'      => $goods_id,
               'goods_sn'      => addslashes($goods['goods_sn']),
               'product_id'    => $product_info['product_id'],
               'goods_name'    => addslashes($goods['goods_name']),
               'market_price'  => $goods['market_price'],
               'goods_attr'    => addslashes($goods_attr),
               'goods_attr_id' => $goods_attr_id,
               'is_real'       => $goods['is_real'],
               'extension_code'=> $goods['extension_code'],
               'is_gift'       => 0,
               'is_shipping'   => $goods['is_shipping']
           );
			
           /* 如果数量不为0，作为基本件插入 */
           if ($num > 0)
           {
               /* 检查该商品是否已经存在在购物车中 */
               $sql = "SELECT rec_id,goods_number FROM " .tablename('wxz_lzl_cart').
                       " WHERE from_user = '" .$_W['fans']['from_user']. "' AND weid = '" .$_W['uniacid']. "' AND goods_id = '$goods_id' ".
                       " AND parent_id = 0 AND goods_attr_id = '" .$goods_attr_id. "' " .
                       " AND rec_type = '0'";
               $row = pdo_fetch($sql);
               
               if($row) //如果购物车已经有此物品，则更新
               {	
                    if($is_direct == 0) {
                                 $num += $row['goods_number'];
                    }
                    $goods_price = $this->get_final_price($goods_id, $num, true, $spec);
                    $sql = "UPDATE " . tablename('wxz_lzl_cart') . " SET goods_number = '$num'" .
                                   " , goods_price = '$goods_price'".
                                   " WHERE from_user = '" .$_W['fans']['from_user']. "' AND weid = '" .$_W['uniacid']. "' AND goods_id = '$goods_id' ".
                                   " AND parent_id = 0 AND goods_attr_id = '" .$goods_attr_id. "' " .
                                   "AND rec_type = '0'";
                    pdo_query($sql);
                    $data['rec_id'] = $row['rec_id'];
               }
               else //购物车没有此物品，则插入
               {
                   $goods_price = $this->get_final_price($goods_id, $num, true, $spec);
                   $param['goods_price']  = max($goods_price, 0);
                   $param['goods_number'] = $num;
                   $param['parent_id']    = 0;
                    pdo_insert('wxz_lzl_cart', $param);
                    $data['rec_id'] = pdo_insertid();
               }
           }
           $data['num'] = $num;
           return $data;
       }
       /**
        *
        * 是否存在规格
        *
        * @access      public
        * @param       array       $goods_attr_id_array        一维数组
        *
        * @return      string
        */
       function is_spec($goods_attr_id_array, $sort = 'asc')
       {
           if (empty($goods_attr_id_array))
           {
               return $goods_attr_id_array;
           }

           //重新排序
           $sql = "SELECT a.attr_type, v.attr_value, v.goods_attr_id
                   FROM " .tablename('wxz_lzl_attribute'). " AS a
                   LEFT JOIN " .tablename('wxz_lzl_goods_attr'). " AS v
                       ON v.attr_id = a.attr_id
                       AND a.attr_type = 1
                   WHERE v.goods_attr_id " . $this->db_create_in($goods_attr_id_array) . "
                   ORDER BY a.attr_id $sort";
           $row = pdo_fetchall($sql);

           $return_arr = array();
           foreach ($row as $value)
           {
               $return_arr['sort'][]   = $value['goods_attr_id'];

               $return_arr['row'][$value['goods_attr_id']]    = $value;
           }

           if(!empty($return_arr))
           {
               return true;
           }
           else
           {
               return false;
           }
       }
       /**
        * 判断某个商品是否正在特价促销期
        *
        * @access  public
        * @param   float   $price      促销价格
        * @param   string  $start      促销开始日期
        * @param   string  $end        促销结束日期
        * @return  float   如果还在促销期则返回促销价，否则返回0
        */
       function bargain_price($price, $start, $end)
       {
           if ($price == 0)
           {
               return 0;
           }
           else
           {
               $time = time();
               if ($time >= $start && $time <= $end)
               {
                   return $price;
               }
               else
               {
                   return 0;
               }
           }
       }
       /**
        * 获得指定的商品属性
        *
        * @access      public
        * @param       array       $arr        规格、属性ID数组
        * @param       type        $type       设置返回结果类型：pice，显示价格，默认；no，不显示价格
        *
        * @return      string
        */
       function get_goods_attr_info($arr, $type = 'pice')
       {
           $attr   = '';

           if (!empty($arr))
           {
               $fmt = "%s:%s[%s] \n";

               $sql = "SELECT a.attr_name, ga.attr_value, ga.attr_price ".
                       "FROM ".tablename('wxz_lzl_goods_attr')." AS ga, ".
                           tablename('wxz_lzl_attribute')." AS a ".
                       "WHERE " .$this->db_create_in($arr, 'ga.goods_attr_id')." AND a.attr_id = ga.attr_id";
               $res = pdo_fetchall($sql);
               
               foreach($res as $key=>$row) {
                   $attr_price = round(floatval($row['attr_price']), 2);
                   $attr .= sprintf($fmt, $row['attr_name'], $row['attr_value'], $attr_price);
               }
               $attr = str_replace('[0]', '', $attr);
           }

           return $attr;
       }
       /**
        * 取指定规格的货品信息
        *
        * @access      public
        * @param       string      $goods_id
        * @param       array       $spec_goods_attr_id
        * @return      array
        */
       function get_products_info($goods_id, $spec_goods_attr_id)
       {
           $return_array = array();

           if (empty($spec_goods_attr_id) || !is_array($spec_goods_attr_id) || empty($goods_id))
           {
               return $return_array;
           }
			
           $goods_attr_array = $this->sort_goods_attr_id_array($spec_goods_attr_id);
			
           if(isset($goods_attr_array['sort']))
           {
               $goods_attr = implode('|', $goods_attr_array['sort']);
				
               $sql = "SELECT * FROM " .tablename('wxz_lzl_products'). " WHERE goods_id = '$goods_id' AND goods_attr = '$goods_attr' LIMIT 0, 1";
			   $return_array = pdo_fetch($sql);
           }
           return $return_array;
       }
       /**
	 * 取得购物车商品
	 * @param   int     $type   类型：默认普通商品
	 * @return  array   购物车商品数组
	 */
	function cart_goods()
	{
		global $_W, $_GPC;
                $condition = '';
                if(isset($_SESSION['selcart_goods']) && !empty($_SESSION['selcart_goods'])) {
                    $condition = $this->db_create_in($_SESSION['selcart_goods'], 'AND c.rec_id');
                }
		$sql = "SELECT c.*, g.goods_img FROM " . tablename('wxz_lzl_cart') . " AS c ".
				"LEFT JOIN ". tablename('wxz_lzl_goods') ." AS g ON c.goods_id = g.goods_id ".
				"WHERE c.weid = '" . $_W['uniacid'] . "' AND c.from_user = '". $_W['fans']['from_user'] ."' $condition" .
				"AND c.rec_type = '0'";

		$arr = pdo_fetchall($sql);

		/* 格式化价格及礼包商品 */
		foreach ($arr as $key => $value)
		{
			$arr[$key]['formated_market_price'] = $this->price_format($value['market_price'], false);
			$arr[$key]['formated_goods_price']  = $this->price_format($value['goods_price'], false);
			$arr[$key]['formated_subtotal']     = $this->price_format($value['goods_price']*$value['goods_number'], false);
		}

		return $arr;
	}
        public function getCartTotal($gid=0) {
		global $_GPC, $_W;
		$condition = '';
		if($gid > 0) {
			$condition = "And goods_id = '$gid'";
		}
		$cartotal = pdo_fetchcolumn("select sum(goods_number) from " . tablename('wxz_lzl_cart') . " where weid = '{$_W['uniacid']}' and from_user='{$_W['fans']['from_user']}' $condition and rec_type=0");
		return empty($cartotal) ? 0 : $cartotal;
	}
        /**
        * 获得订单中的费用信息
        *
        * @access  public
        * @param   array   $order
        * @param   array   $goods
        * @param   array   $consignee
        * @param   bool    $is_gb_deposit  是否团购保证金（如果是，应付款金额只计算商品总额和支付费用，可以获得的积分取 $gift_integral）
        * @return  array
        */
       function order_fee($order, $goods)
       {
           global $_W, $_GPC;
           /* 初始化订单的扩展code */
           if (!isset($order['extension_code']))
           {
               $order['extension_code'] = '';
           }

           $total  = array('real_goods_count' => 0,
                           'goods_price'      => 0,
                           'market_price'     => 0,
                           'shipping_fee'     => 0
                           );

           /* 商品总价 */
           foreach ($goods AS $val)
           {
               /* 统计实体商品的个数 */
               if ($val['is_real'])
               {
                   $total['real_goods_count']++;
               }

               $total['goods_price']  += $val['goods_price'] * $val['goods_number'];
               $total['market_price'] += $val['market_price'] * $val['goods_number'];
           }

           $total['saving']    = $total['market_price'] - $total['goods_price'];
           $total['save_rate'] = $total['market_price'] ? round($total['saving'] * 100 / $total['market_price']) . '%' : 0;

           $total['goods_price_formated']  = $this->price_format($total['goods_price'], false);
           $total['market_price_formated'] = $this->price_format($total['market_price'], false);
           $total['saving_formated']       = $this->price_format($total['saving'], false);
			
           /* 配送费用 */
           $shipping_cod_fee = NULL;
           if ($order['shipping_id'] > 0 && $total['real_goods_count'] > 0)
           {
               $shipping_info = $this->shipping_area_info($order['shipping_id']);
               if (!empty($shipping_info))
               {
                    $weight_price = $this->cart_weight_price();
                   // 查看购物车中是否全为免运费商品，若是则把运费赋为零
                   $sql = 'SELECT count(*) FROM ' . tablename('wxz_lzl_cart') . " WHERE  `weid` = '" . $_W['uniacid']. "' AND `from_user` = '". $_W['fans']['from_user'] ."' AND `is_shipping` = 0";
                   $shipping_count = pdo_fetchcolumn($sql);

                   $total['shipping_fee'] = ($shipping_count == 0) ? 0 :  $this->shipping_fee($shipping_info, $weight_price['weight'], $total['goods_price'], $weight_price['number']);
               }
           }
           $total['shipping_fee_formated']    = $this->price_format($total['shipping_fee'], false);
           //统计费用
           $total['amount'] = $total['goods_price'] + $total['shipping_fee'];
           $total['amount_formated']  = $this->price_format($total['amount'], false);
           $total['formated_goods_price']  = $this->price_format($total['goods_price'], false);
           $total['formated_market_price'] = $this->price_format($total['market_price'], false);
           $total['formated_saving']       = $this->price_format($total['saving'], false);
           /* 保存订单信息 */
           $_SESSION['flow_order'] = $order;
           
           return $total;
       }
       /**
        * 获得购物车中商品的总重量、总价格、总数量
        *
        * @access  public
        * @param   int     $type   类型：默认普通商品
        * @return  array
        */
       function cart_weight_price()
       {
           global $_W, $_GPC;
           $package_row['weight'] = 0;
           $package_row['amount'] = 0;
           $package_row['number'] = 0;
            $condition = '';
            if(isset($_SESSION['selcart_goods']) && !empty($_SESSION['selcart_goods'])) {
                $condition = $this->db_create_in($_SESSION['selcart_goods'], 'AND c.rec_id');
            }
           /* 获得购物车中非超值礼包商品的总重量 */
           $sql    = 'SELECT SUM(g.goods_weight * c.goods_number) AS weight, ' .
                           'SUM(c.goods_price * c.goods_number) AS amount, ' .
                           'SUM(c.goods_number) AS number '.
                       'FROM ' . tablename('wxz_lzl_cart') . ' AS c '.
                       'LEFT JOIN ' . tablename('wxz_lzl_goods') . ' AS g ON g.goods_id = c.goods_id '.
                       "WHERE c.weid = '" . $_W['uniacid'] . "' AND from_user = '". $_W['fans']['from_user'] ."'" .
                       "$condition AND g.is_shipping = 0";
           $row = pdo_fetch($sql);

           $packages_row['weight'] = floatval($row['weight']) ;
           $packages_row['amount'] = floatval($row['amount']) ;
           $packages_row['number'] = intval($row['number']) ;
           /* 格式化重量 */
           //$packages_row['formated_weight'] = formated_weight($packages_row['weight']);

           return $packages_row;
       }
       /**
        * 取得某配送方式对应于某收货地址的区域信息
        * @param   int     $shipping_id        配送方式id
        * @param   array   $region_id_list     收货人地区id数组
        * @return  array   配送区域信息（config 对应着反序列化的 configure）
        */
       function shipping_area_info($shipping_id)
       {
           $sql = 'SELECT s.shipping_code, s.shipping_name, ' .
                       's.shipping_desc, s.insure, s.support_cod, a.* ' .
                   'FROM ' . tablename('wxz_lzl_shipping') . ' AS s, ' .
                       tablename('wxz_lzl_shipping_area') . ' AS a ' .
                   "WHERE a.shipping_area_id = '$shipping_id' " .
                   ' AND a.shipping_id = s.shipping_id';
           $row = pdo_fetch($sql);

           return $row;
       }
       /**
        * 取得可用的配送方式列表
        * @param   array   $region_id_list     收货人地区id数组（包括国家、省、市、区）
        * @return  array   配送方式数组
        */
       function available_shipping_list()
       {
           $sql = 'SELECT s.shipping_id, s.shipping_code, s.shipping_name, ' .
                       's.shipping_desc,  a.* ' .
                   'FROM ' . tablename('wxz_lzl_shipping') . ' AS s, ' .
                       tablename('wxz_lzl_shipping_area') . ' AS a ' .
                   'WHERE a.shipping_id = s.shipping_id ORDER BY s.shipping_order';

           return pdo_fetchall($sql);
       }
       /**
        * 计算运费
        * @param   string  $shipping_code      配送方式代码
        * @param   mix     $shipping_config    配送方式配置信息
        * @param   float   $goods_weight       商品重量
        * @param   float   $goods_amount       商品金额
        * @param   float   $goods_number       商品数量
        * @return  float   运费
        */
       function shipping_fee($shipping_info, $goods_weight, $goods_amount, $goods_number='')
       {
           if ($shipping_info['free_money'] > 0 && $goods_amount >= $shipping_info['free_money'])
            {
                return 0;
            }
            else
            {
                @$fee = $shipping_info['base_fee'];
                 if ($shipping_info['fee_compute_mode'] == 1)
                {
                    $fee = $goods_number * $shipping_info['item_fee'];
                }
                else
                {
                    if ($goods_weight > 1)
                    {
                        $fee += (ceil(($goods_weight - 1))) * $shipping_info['step_fee'];
                    }
                }

                return $fee;
            }
       }
        protected function pay($params = array(), $mine = array()) {
		global $_W;
		$params['module'] = $this->module['name'];
		$sql = 'SELECT * FROM ' . tablename('core_paylog') . ' WHERE `uniacid`=:uniacid AND `module`=:module AND `tid`=:tid';
		$pars = array();
		$pars[':uniacid'] = $_W['uniacid'];
		$pars[':module'] = $params['module'];
		$pars[':tid'] = $params['tid'];
		$log = pdo_fetch($sql, $pars);
		if(!empty($log) && $log['status'] == '1') {
			message('这个订单已经支付成功, 不需要重复支付.');
		}
		$setting = uni_setting($_W['uniacid'], array('payment', 'creditbehaviors'));
		if(!is_array($setting['payment'])) {
			message('没有有效的支付方式, 请联系网站管理员.');
		}
		$log = pdo_get('core_paylog', array('uniacid' => $_W['uniacid'], 'module' => $params['module'], 'tid' => $params['tid']));
		if (empty($log)) {
			$log = array(
				'uniacid' => $_W['uniacid'],
				'acid' => $_W['acid'],
				'openid' => $_W['member']['uid'],
				'module' => $this->module['name'], 				'tid' => $params['tid'],
				'fee' => $params['fee'],
				'card_fee' => $params['fee'],
                                'uniontid' => date('YmdHis') . $moduleid . random(8,1),
				'status' => '0',
				'is_usecard' => '0',
			);
			pdo_insert('core_paylog', $log);
		}
		$pay = $setting['payment'];
		$pay['credit']['switch'] = false;
		$pay['delivery']['switch'] = false;

                //微信支付
                $params['uniontid'] = $log['uniontid'];
		$setting = uni_setting($_W['uniacid'], array('payment'));
		if($setting['payment']['wechat']['switch']==2){
			$setting = uni_setting($setting['payment']['wechat']['borrow'], array('payment'));
		}

		$wechat = $setting['payment']['wechat'];

		$sql = 'SELECT `key`,`secret` FROM ' . tablename('account_wechats') . ' WHERE `acid`=:acid';
		$row = pdo_fetch($sql, array(':acid' => $wechat['account']));
		$wechat['appid'] = $row['key'];
		$wechat['secret'] = $row['secret'];

		if ($wechat['version'] == 1) {
			$option = array();
			$option['appId'] = $wechat['appid'];
			$option['timeStamp'] = time();
			$option['nonceStr'] = random(8);
			$package = array();
			$package['bank_type'] = 'WX';
			$package['body'] = $params['title'];
			$package['attach'] = $_W['uniacid'];
			$package['partner'] = $wechat['partner'];
			$package['out_trade_no'] = $params['uniontid'];
			$package['total_fee'] = $params['fee'];
			$package['fee_type'] = '1';
			$package['notify_url'] = $_W['siteroot'] . 'addons/wxz_wzb/shopnotify.php';
			$package['spbill_create_ip'] = CLIENT_IP;
			$package['time_start'] = date('YmdHis', time());
			$package['time_expire'] = date('YmdHis', time() + 600);
			$package['input_charset'] = 'UTF-8';
			ksort($package);
			$string1 = '';
			foreach($package as $key => $v) {
				if (empty($v)) {
					continue;
				}
				$string1 .= "{$key}={$v}&";
			}
			$string1 .= "key={$wechat['key']}";
			$sign = strtoupper(md5($string1));

			$string2 = '';
			foreach($package as $key => $v) {
				$v = urlencode($v);
				$string2 .= "{$key}={$v}&";
			}
			$string2 .= "sign={$sign}";
			$option['package'] = $string2;

			$string = '';
			$keys = array('appId', 'timeStamp', 'nonceStr', 'package', 'appKey');
			sort($keys);
			foreach($keys as $key) {
				$v = $option[$key];
				if($key == 'appKey') {
					$v = $wechat['signkey'];
				}
				$key = strtolower($key);
				$string .= "{$key}={$v}&";
			}
			$string = rtrim($string, '&');

			$option['signType'] = 'SHA1';
			$option['paySign'] = sha1($string);
			
			//$result = array('s'=>'-1','msg'=>$option);
			//echo json_encode($result);exit;

		} else {

			$package = array();
			$package['appid'] = $wechat['appid'];
			$package['mch_id'] = $wechat['mchid'];
			$package['nonce_str'] = random(8);
			$package['body'] = $params['title'];
			$package['attach'] = $_W['uniacid'];
			$package['out_trade_no'] = $params['uniontid'];
			$package['total_fee'] = $params['fee'] * 100;
			$package['spbill_create_ip'] = CLIENT_IP;
			$package['time_start'] = date('YmdHis', time());
			$package['time_expire'] = date('YmdHis', time() + 600);
			$package['notify_url'] = $_W['siteroot'] . 'addons/wxz_wzb/shopnotify.php';
			$package['trade_type'] = 'JSAPI';
			$package['openid'] = empty($wechat['openid']) ? $_W['fans']['from_user'] : $wechat['openid'];
                        
			ksort($package, SORT_STRING);
			$string1 = '';
			foreach($package as $key => $v) {
				if (empty($v)) {
					continue;
				}
				$string1 .= "{$key}={$v}&";
			}
			$string1 .= "key={$wechat['signkey']}";
			$package['sign'] = strtoupper(md5($string1));
			$dat = array2xml($package);
			$response = ihttp_request('https://api.mch.weixin.qq.com/pay/unifiedorder', $dat);

			if (is_error($response)) {
				$result = array('s'=>'-1','msg'=>strval($xml->return_msg));
			
				echo json_encode($result);exit;
				return $response;
			}
			
			$xml = @isimplexml_load_string($response['content'], 'SimpleXMLElement', LIBXML_NOCDATA);
					
			if (strval($xml->return_code) == 'FAIL') {
				$result = array('s'=>'-1','msg'=>strval($xml->return_msg));
			
				echo json_encode($result);exit;

			}

			if (strval($xml->result_code) == 'FAIL') {
				$result = array('s'=>'-1','msg'=>strval($xml->return_msg));
			
				echo json_encode($result);exit;

			}
			$prepayid = $xml->prepay_id;
			$option['appId'] = $wechat['appid'];
			$option['timeStamp'] = TIMESTAMP;
			$option['nonceStr'] = random(8);
			$option['package'] = 'prepay_id='.$prepayid;
			$option['signType'] = 'MD5';
			ksort($option, SORT_STRING);
			foreach($option as $key => $v) {
				$string .= "{$key}={$v}&";
			}
			$string .= "key={$wechat['signkey']}";
			$option['paySign'] = strtoupper(md5($string));
			//$result = array('s'=>'1','msg'=>$option);
			//echo json_encode($result);exit;
		}
		include $this->template('store/paycenter');
	}
       /**
        * 检查订单中商品库存
        *
        * @access  public
        * @param   array   $arr
        *
        * @return  void
        */
       function check_stock($cart_goods)
       {
           $info_arr = array('msg' => '');
           global $_W, $_GPC;
           foreach ($cart_goods AS $key => $val)
           {
               $sql = "SELECT g.goods_name, g.goods_number, c.product_id ".
                       "FROM " .tablename('wxz_lzl_goods'). " AS g, ".
                           tablename('wxz_lzl_cart'). " AS c ".
                       "WHERE g.goods_id = c.goods_id AND c.rec_id = '". $val['rec_id'] ."'";
               $goods = pdo_fetch($sql);

               //检查输入的商品数量是否有效
                if ($goods['goods_number'] < $val['goods_number'])
                {
                    $info_arr['redirect'] = $this->createMobileUrl('store', array('act' => 'cart'));
                    $info_arr['msg'] = '非常抱歉，您选择的商品 '. $goods['goods_name'] .' 的库存数量只有 '. $goods['goods_number'] .'，您最多只能购买 '. $goods['goods_number'] .' 件。';
                    return $info_arr;
                }

                /* 是货品 */
                if (!empty($val['product_id']))
                {
                    $sql = "SELECT product_number FROM " .tablename('wxz_lzl_products'). " WHERE goods_id = '" . $val['goods_id'] . "' AND product_id = '" . $val['product_id'] . "'";
                    $product_number = pdo_fetchcolumn($sql);
                    if ($product_number < $val['goods_number'])
                    {
                        $info_arr['redirect'] = $this->createMobileUrl('store', array('act' => 'cart'));
                        $info_arr['msg'] = '非常抱歉，您选择的商品 '. $goods['goods_name'] .' 的库存数量只有 '. $goods['goods_number'] .'，您最多只能购买 '. $goods['goods_number'] .' 件。';
                        return $info_arr;
                    }
                }
           }
           return $info_arr;
       }
       function done_order($allgoods, $address) {
            global $_W, $_GPC;
            $errmsg = array('msg' => '');
            $order = array(
                    'shipping_id'     => intval($_GPC['shipping']),
                    'weid'         => $_W['weid'],
                    'consignee'         => $address['username'],
                    'from_user' => $_W['fans']['from_user'],
                    'address' =>  $address['username'] . '|' . $address['mobile'] . '|' . $address['zipcode']
                                                    . '|' . $address['province'] . '|' . $address['city'] . '|' .
                                                    $address['district'] . '|' . $address['address'],
                    'add_time'        => time()
                    );

            /* 订单中的总额 */
            $total = $this->order_fee($order, $allgoods);
            $order['goods_amount'] = $total['goods_price'];

            /* 配送方式 */
            if ($order['shipping_id'] > 0)
            {
                    $shipping = $this->shipping_area_info($order['shipping_id']);
                    $order['shipping_name'] = addslashes($shipping['shipping_name']);
            }
            $order['shipping_fee'] = $total['shipping_fee'];
            $order['order_amount']  = number_format($total['amount'], 2, '.', '');

            /* 插入订单表 */
            //$error_no = 0;
            //do
            //{
                $order['order_sn'] = date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
                pdo_insert('wxz_lzl_order_info', $order);
            //}
            //while ($error_no == 1062); //如果是订单号重复则重新提交数据
            $orderid = pdo_insertid();
            $order['order_id'] = $orderid;

            /* 插入订单商品 */
            $where = '';
            if(isset($_SESSION['selcart_goods']) && !empty($_SESSION['selcart_goods'])){
                $where = $this->db_create_in($_SESSION['selcart_goods'], 'AND rec_id');
            }
            $sql = "INSERT INTO " . tablename('wxz_lzl_order_goods') . "( " .
                                    "order_id, goods_id, goods_name, goods_sn, product_id, goods_number, market_price, ".
                                    "goods_price, goods_attr, is_real, extension_code, parent_id, weid, goods_attr_id) ".
                            " SELECT '$orderid', goods_id, goods_name, goods_sn, product_id, goods_number, market_price, ".
                                    "goods_price, goods_attr, is_real, extension_code, parent_id, weid, goods_attr_id".
                            " FROM " .tablename('wxz_lzl_cart') .
                            " WHERE weid = '".$_W['weid']."' AND from_user = '". $_W['fans']['from_user'] ."' $where";
            pdo_query($sql);

            /* 如果使用库存，且下订单时减库存，则减少库存 */
            if (isset($_W['store_info']['stock_dec_time']) && $_W['store_info']['stock_dec_time'] == 1)
             {
                    $this->change_order_goods_storage($order['order_id'], true);
             }

            /* 清空购物车 */
            $sql = "DELETE FROM " . tablename('wxz_lzl_cart') ." WHERE weid = '". $_W['uniacid'] ."' AND from_user = '". $_W['fans']['from_user'] ."' $where"; 
            pdo_query($sql);      

            unset($_SESSION['flow_order']);
            //$row['url'] = $this->createMobileUrl('pay', array('oid' => $orderid));
            //message('提交订单成功,现在跳转到付款页面...', $this->createMobileUrl('pay', array('oid' => $orderid)), 'ajax');
            //订单信息
            $goods_title = '';
            $order = pdo_fetch("SELECT * FROM " . tablename('wxz_lzl_order_info') . " WHERE order_id = :order_id AND weid = :weid", array(':order_id' => $orderid, ':weid' => $_W['uniacid']));
            $sql = 'SELECT `goods_name` FROM ' . tablename('wxz_lzl_order_goods') . " WHERE `order_id` = '$orderid'";
            $goodsTitle = pdo_fetchall($sql);
            foreach($goodsTitle as $key=>$val){
                $goods_title .= $val['goods_name'].",";
            }

            $params['tid'] = $orderid;
            $params['user'] = $_W['fans']['from_user'];
            $params['title'] = substr($goods_title, 0, -1);
            $params['ordersn'] = $order['order_sn'];
            $params['virtual'] = $order['goodstype'] == 2 ? true : false;
            $params['fee'] = $order['order_amount'];
            
            return $params;
        }
        /**
        * 改变订单中商品库存
        * @param   int     $order_id   订单号
        * @param   bool    $minus     是否减少库存
        * @param   bool    $storage     减库存的时机，1，下订单时；0，发货时；
        */
       function change_order_goods_storage($order_id, $minus = true, $storage = 0)
       {
           /* 查询订单商品信息 */
           switch ($storage)
           {
               case 0 :
                   $sql = "SELECT goods_id, SUM(goods_number) AS num, product_id FROM " . tablename('wxz_lzl_order_goods') .
                           " WHERE order_id = '$order_id' GROUP BY goods_id, product_id";
               break;

               case 1 :
                   $sql = "SELECT goods_id, SUM(goods_number) AS num, product_id FROM " . tablename('wxz_lzl_order_goods') .
                           " WHERE order_id = '$order_id' GROUP BY goods_id, product_id";
               break;
           }

           $allgoods = pdo_fetchall($sql);
           foreach ($allgoods as $row) {
                if ($minus)
                   {
                       $this->change_goods_storage($row['goods_id'], $row['product_id'], - $row['num']);
                   }
                   else
                   {
                       $this->change_goods_storage($row['goods_id'], $row['product_id'], $row['num']);
                   }
            }
       }
       /**
        * 商品库存增与减 货品库存增与减
        *
        * @param   int    $good_id         商品ID
        * @param   int    $product_id      货品ID
        * @param   int    $number          增减数量，默认0；
        *
        * @return  bool               true，成功；false，失败；
        */
       function change_goods_storage($good_id, $product_id, $number = 0)
       {
           if ($number == 0)
           {
               return true; // 值为0即不做、增减操作，返回true
           }

           if (empty($good_id) || empty($number))
           {
               return false;
           }

           $number = ($number > 0) ? '+ ' . $number : $number;

           /* 处理货品库存 */
           $products_query = true;
           if (!empty($product_id))
           {
               $sql = "UPDATE " . tablename('wxz_lzl_products') ."
                       SET product_number = product_number $number
                       WHERE goods_id = '$good_id'
                       AND product_id = '$product_id'
                       LIMIT 1";
               $products_query = pdo_query($sql);
           }

           /* 处理商品库存 */
           $sql = "UPDATE " . tablename('wxz_lzl_goods') ."
                   SET goods_number = goods_number $number
                   WHERE goods_id = '$good_id'
                   LIMIT 1";
           $query = pdo_query($sql);

           if ($query && $products_query)
           {
               return true;
           }
           else
           {
               return false;
           }
        }
        //商城公共函数end
}