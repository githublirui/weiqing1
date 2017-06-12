<?php

/**
 * 抓取目标场景坐标 xml
 */
global $_W, $_GPC;
require_once WXZ_PANORAMA . '/source/Scene.class.php';

$data = $_GPC['data'];
$datas = explode("|", $data);
$id = $datas[0];
$styleid = $datas[1];
$h = $datas[2];
$v = $datas[3];

$scenerow = Scene::getById($id);

$xml = "";
$xml .= '<?xml version="1.0" encoding="UTF-8"?>' . "\r\n";
$xml .= '<krpano version="1.0.8" onstart="action(start);" >' . "\r\n";

$xml .= '<action name="start">' . "\r\n";
$xml .= 'loadscene(scene1, null, MERGE);' . "\r\n";
$xml .= '</action>' . "\r\n";

$xml .= '<events onloadcomplete="" />' . "\r\n";

$xml .= '<scene name="scene1">' . "\r\n";
$xml .= '<view fov="80" fisheye="0" hlookat="' . $h . '" vlookat="' . $v . '" fovmin="60" fovmax="120" />' . "\r\n";

$xml .= '<preview url="' . tomedia($scenerow['preview']) . '" />' . "\r\n";
$xml .= '<image type="CUBE">' . "\r\n";
$xml .= '<left url="' . tomedia($scenerow['left']['pano']) . '" />' . "\r\n";
$xml .= '<front url="' . tomedia($scenerow['front']['pano']) . '" />' . "\r\n";
$xml .= '<right url="' . tomedia($scenerow['right']['pano']) . '" />' . "\r\n";
$xml .= '<back url="' . tomedia($scenerow['back']['pano']) . '" />' . "\r\n";
$xml .= '<up url="' . tomedia($scenerow['up']['pano']) . '" />' . "\r\n";
$xml .= '<down url="' . tomedia($scenerow['down']['pano']) . '" />' . "\r\n";
$xml .= '</image>' . "\r\n";

$xml .= '<hotspot name="introimage" alpha="1" ath="' . $h . '" atv="' . $v . '" url="%SWFPATH%/images/introimage.png" ondown="draghotspot();" onclick="getback();"  keep="true" align="center"/>' . "\r\n";

$xml .= "<action name=\"hotspot_animate\">\r\n";
$xml .= "inc(frame,1,get(lastframe),0);\r\n";
$xml .= "mul(ypos,frame,frameheight);\r\n";
$xml .= "txtadd(crop,'0|',get(ypos),'|',get(framewidth),'|',get(frameheight));\r\n";
$xml .= "delayedcall(%1, if(loaded, hotspot_animate(%1) ) );\r\n";
$xml .= "</action>\r\n";

$xml .= '</scene>' . "\r\n";


$xml .= "<action name=\"draghotspot\">\r\n";
$xml .= "<![CDATA[\r\n";
$xml .= "if(%1 != dragging,\r\n";
$xml .= "spheretoscreen(ath, atv, hotspotcenterx, hotspotcentery);\r\n";
$xml .= "sub(drag_adjustx, mouse.stagex, hotspotcenterx); \r\n";
$xml .= "sub(drag_adjusty, mouse.stagey, hotspotcentery); \r\n";
$xml .= "draghotspot(dragging);\r\n";
$xml .= ",\r\n";
$xml .= "if(pressed,\r\n";
$xml .= "sub(dx, mouse.stagex, drag_adjustx);\r\n";
$xml .= "sub(dy, mouse.stagey, drag_adjusty);\r\n";
$xml .= "screentosphere(dx, dy, ath, atv);\r\n";
$xml .= "copy(print_ath, ath);\r\n";
$xml .= "copy(print_atv, atv);\r\n";
$xml .= "roundval(print_ath, 3);\r\n";
$xml .= "roundval(print_atv, 3);\r\n";
$xml .= "txtadd(plugin[hotspotinfo].html, '&lt;hotspot name=\"',get(name),'\"[br]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ath=\"',get(print_ath),'\"[br]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;atv=\"',get(print_atv),'\"[br]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;...[br]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/&gt;');\r\n";
$xml .= "delayedcall(0, draghotspot(dragging) );\r\n";
$xml .= ");\r\n";
$xml .= ");\r\n";
$xml .= "]]>\r\n";
$xml .= "</action>\r\n";

$xml .= '<action name="getback">' . "\r\n";
$xml .= 'js(back(get(hotspot[introimage].ath),get(hotspot[introimage].atv)));' . "\r\n";
$xml .= '</action>' . "\r\n";
$xml .= '</krpano>' . "\r\n";

echo $xml;
?>
