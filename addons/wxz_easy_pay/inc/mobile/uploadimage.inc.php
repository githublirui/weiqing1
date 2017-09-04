<?php
ini_set('post_max_size', '12M'); 
ini_set('upload_max_filesize','10M');
error_reporting("0");

global $_GPC, $_W;
load()->model('mc');
		$fan = mc_fansinfo($_W['openid']);
		if (!empty($fan) && !empty($fan['openid'])) {
			$userinfo = $fan;
		}else{
			$userinfo = mc_oauth_userinfo();
		}
require_once "../addons/wxz_easy_pay/pay/imagecuter.php";
$path = "../addons/wxz_easy_pay/upload/";  

if(empty($_GPC['goodsName'])){
	message('产品名称不能为空');
	exit();
}
if(empty($_GPC['goodsPrice'])){
	message('产品价格不能为空');
	exit();
}	
if(empty($_GPC['goodsPrice'])){
	message('产品价格不能为空');
	exit();
}								
								
								
								
								
								
								//上传商品图片	image/jpeg							
        $valid_formats = array("image/gif", "image/jpeg", "image/png", "image/bmp","image/pjpeg","application/octet-stream");  
        if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {  
                $name = $_FILES['file']['name'];  
                $size = $_FILES['file']['size'];
				$type = $_FILES['file']['type'];  
				
				if($_FILES['file']['error']==1){
					message('上传图片太大，请修改服务器配置');
					exit();
				}else if($_FILES['file']['error'] !=0){
					message('上传出错');
					exit();
				}
                // print_R($_FILES);die;
                if(strlen($name)){  
                   
                   if(in_array($type,$valid_formats)){  
                  // if(1){  
                        if($size<(1024*1024*8)){  
                                
								$actual_image_name = time().rand(1,11).".jpg";  
                                $tmp = $_FILES['file']['tmp_name']; 
								load()->func('file');
								mkdirs(MODULE_ROOT  . '/upload',0777);

                                if(move_uploaded_file($tmp, $path.$actual_image_name)){        
									$p_img_url = "../addons/wxz_easy_pay/upload/".$actual_image_name;
								//	var_dump(exif_read_data($tmp));die;
									//echo $p_img_url;die;
										//更新产品图片
										
										$goodsPostNum = (int)$_GPC['goodsPostNum'];
										
										//将商品信息存入数据库
										$dat['set'] = array(
													'goodsName'=>$_GPC['goodsName'],
													'goodsPrice'=>$_GPC['goodsPrice'],
													'goodsStock'=>$_GPC['goodsStock'],
													'postage'=>$_GPC['goodsPost'],
													'goodsUnit'=>$_GPC['goodsUnit'],
													'goodsPostNum'=>$goodsPostNum,
													'goodsImg'=>$p_img_url,
													'goodsSince'=>$_GPC['goodsSince'],
													'goodsInfo'=>$_GPC['goodsInfo'],
													'openid'=>$userinfo['openid'],
													'nickname'=>$userinfo['nickname'],
													'headimgurl'=>$userinfo['headimgurl'],
													'add_time'=>$userinfo['add_time'],
													'uid'=>$userinfo['uid']
											);
			
			
								$result = pdo_insert('hangyi_product', $dat['set']);
								$pid = pdo_insertid();
								if(!$pid){
									message('商品信息添加失败');
									exit();
								}
								
										
										
                                    //    $result = pdo_query("UPDATE ".tablename('hangyi_product')." SET `goodsImg`='".$p_img_url."'  WHERE id = '".$pid."' limit 1");
				
                                       // echo "<img src='./upload/".$actual_image_name."'  class='preview'>";  
                                }else{  
                                   message('上传失败');
									exit();
								}
								
								
								
						}else{
							message('上传图片最大 2 MB');
							exit();
							echo "上传图片最大 2 MB"; 
						}							
                    }else{
						message('无效的图片格式');
						exit();
						echo "无效的图片格式";
					}
				}else{
					message('请选择图片');
					exit();
					echo "请选择图片";
				}
        
         }
	
	//header('Content-Type: image/jpeg');
 // imagejpeg($main_source);							
	
$main_url = "../addons/wxz_easy_pay/images/buy.png";
list($main_width, $main_height) = getimagesize($main_url);

$chanping = $p_img_url;
list($chanping_width, $chanping_height) = getimagesize($chanping);

//把产品放大或缩小和main一样的宽度
//echo imagecreatefromstring(file_get_contents($chanping));

$newwidth = $main_width;
$newheight = ($main_width/$chanping_width)*$chanping_height;





function imageCropper($source_path, $target_width, $target_height){
  $source_info  = getimagesize($source_path);
  $source_width = $source_info[0];
  $source_height = $source_info[1];
  $source_mime  = $source_info['mime'];
  $source_ratio = $source_height / $source_width;
  $target_ratio = $target_height / $target_width;
  if ($source_ratio > $target_ratio){
    // image-to-height
    $cropped_width = $source_width;
    $cropped_height = $source_width * $target_ratio;
    $source_x = 0;
    $source_y = ($source_height - $cropped_height) / 2;
  }elseif ($source_ratio < $target_ratio){
    //image-to-widht
    $cropped_width = $source_height / $target_ratio;
    $cropped_height = $source_height;
    $source_x = ($source_width - $cropped_width) / 2;
    $source_y = 0;
  }else{
    //image-size-ok
    $cropped_width = $source_width;
    $cropped_height = $source_height;
    $source_x = 0;
    $source_y = 0;
  }
  switch ($source_mime){
    case 'image/gif':
      $source_image = imagecreatefromgif($source_path);
      break;
    case 'image/jpeg':
      $source_image = imagecreatefromjpeg($source_path);
      break;
    case 'image/png':
      $source_image = imagecreatefrompng($source_path);
      break;
    default:
      return ;
      break;
  }
  $target_image = imagecreatetruecolor($target_width, $target_height);
  $cropped_image = imagecreatetruecolor($cropped_width, $cropped_height);
  // copy
  imagecopy($cropped_image, $source_image, 0, 0, $source_x, $source_y, $cropped_width, $cropped_height);
  // zoom
  imagecopyresampled($target_image, $cropped_image, 0, 0, 0, 0, $target_width, $target_height, $cropped_width, $cropped_height);
  return $target_image;
  
  header('Content-Type: image/jpeg');
  imagejpeg($target_image,null,100);
  imagedestroy($source_image);
  imagedestroy($target_image);
  imagedestroy($cropped_image);
}
$main_source = imagecreatefrompng($main_url);
if($newheight>800){
	$newheight =800;
}else if($newheight<800){
	//裁剪掉主图尺寸
	$cjwidth = 800- $newheight;
	$target_width_c = $main_width;
	$target_height_c = $main_height-$cjwidth;
	$target_width_c_image = imagecreatetruecolor($main_width, $target_height_c);
	imagecopyresampled($target_width_c_image, $main_source, 0, 0, 0, $cjwidth, $main_width, $target_height_c, $main_width, $target_height_c);
	
}
if($target_width_c_image){
	$main_source = $target_width_c_image;
}


$bb = imageCropper($chanping,$newwidth,$newheight);




imagecopyresampled($main_source, $bb, 0, 0, 0, 0, $newwidth, $newheight, $newwidth, $newheight);

include '../addons/wxz_easy_pay/pay/example/phpqrcode/phpqrcode.php'; 


$url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."/app".$this->createMobileUrl('productdetail')."&pid=".$pid;
$url2 = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."/app".$this->createMobileUrl('ordershow')."&orderid=".$pid;
$url3 = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];

$url = str_replace("/app./index.php","",$url);
$url2 = str_replace("/app./index.php","",$url2);
$url3 = str_replace("app/index.php","",$url3);

$codeimg = $path."qrcode".rand(1,10).time().".png";
QRcode::png($url,$codeimg,"L",3.7);

$info=imagecreatefrompng($codeimg);
$pay_path = "../addons/wxz_easy_pay/images/pay.png";
$info_pay=imagecreatefrompng($pay_path);

load()->func('communication'); 

$response = ihttp_get($_W['fans']['headimgurl']);
													  
$head_path = "../addons/wxz_easy_pay/upload/head".time().".jpg";
file_put_contents($head_path,$response['content']);
$headimgurl = imageCropper($head_path,"65","65");
//list($info_width, $info_height) = getimagesize($info);

imagecopyresampled($main_source, $info, 410, $newheight-25, 0, 0, 165, 165, 165, 165);
imagecopyresampled($main_source, $info_pay, 110, $newheight+14, 0, 0, 217, 65, 217, 65);
imagecopyresampled($main_source, $headimgurl, 30, $newheight+15, 0, 0, 65, 65, 65, 65);
$text = "卖家 ：".$_W['fans']['nickname'];

$text2 =$_GPC['goodsPrice']." 元";
if($_GPC['goodsUnit']){
	$text2 =$_GPC['goodsPrice']." 元 / ".$_GPC['goodsUnit'];
}
$text3 = $_GPC['goodsName'];
$text4 = '技术支持：微小智';
$font = '../addons/wxz_easy_pay/font/msyhbd.ttf';
$font2 = '../addons/wxz_easy_pay/font/msyh.ttf';
$white = imagecolorallocate($main_source, 255, 255, 255);
$grey = imagecolorallocate($main_source, 0, 0, 0);
imagettftext($main_source, 20, 0, 30+2, $newheight-20+2, $grey, $font2, $text);
imagettftext($main_source, 20, 0, 30, $newheight-20, $white, $font2, $text);

imagettftext($main_source, 26, 0, 30, $newheight+160, $white, $font, $text2);
imagettftext($main_source, 20, 0, 30, $newheight+210, $white, $font, $text3);


$setting = pdo_get('hangyi_hbset', array('uniacid' => $_W['uniacid']));
	if($setting['img_text']){
		$text4 = $setting['img_text'];
	} 
//版权居中
$fontBox = imagettfbbox(18, 0, $font2, $text4);
//ceil(($width - $fontBox[2]) / 2)
//imagettftext($main_source, 18, 0, 120, $newheight+295, $white, $font2, $text4);
imagettftext($main_source, 18, 0, ceil(($main_width - $fontBox[2]) / 2), $newheight+295, $white, $font2, $text4);


ob_start(); 
imagejpeg($main_source,null,100);
$img = ob_get_contents(); 
ob_end_clean();
$cur_time = time().rand(1,40);	
$df =file_put_contents("../addons/wxz_easy_pay/upload/".$cur_time.'.png',$img);					
	

		if($df){
			$file_url = "../addons/wxz_easy_pay/upload/".$cur_time.'.png';
			//更新支付图片
			$result = pdo_query("UPDATE ".tablename('hangyi_product')." SET `goodsCode`='".$file_url."'  WHERE id = '".$pid."' limit 1");
			include $this->template('upimg');
		}							
									
	

			