<?php echo '<?xml version="1.0" encoding="UTF-8"?>
<krpano onstart="action(start);" >
<skin_settings uishowpic="" />
<skin_settings openautonext="0" />
<autorotate enabled="false" waittime="1" accel="1.5" speed="5" horizon="0"/>
<action name="start">
delayedcall(autonexttimer,15, autonextscene(););
loadscene(scene1, null, MERGE);
</action>
<textstyle name="DEFAULT" effect="" bordercolor="0xF6F6F6" backgroundcolor="0xFEFEFE" background="True" edge="bottom" blendmode="NORMAL" alpha="0.8" fadeintime="0" fadetime="0" showtime="0.1" noclip="False" yoffset="-3" xoffset="0" textalign="none" origin="cursor" textcolor="0x000000" border="True" italic="False" bold="False" fontsize="12" font="Arial, Helvetica, sans-serif"/>
<layer name="introimage" url="%SWFPATH%introimage.png" alpha="0.8" align="center" onclick="hideintroimage();" keep="true" visible="true" />
<action name="hideintroimage">if(layer[introimage].enabled,set(layer[introimage].enabled,false);tween(layer[introimage].alpha, 0.0, 0.5, default, removelayer(introimage)););</action>
<plugin zorder="20000" visible="false" name="closeautonext" keep="true" url="%SWFPATH%/autonextclose.png" align="top" x="0" y="10" alpha="1" scale="1" onclick="autonextchange();" />
<scene name="scene1" scenetitle="中俊理想城西北景观水系" thumburl="" nowpic="" onstart="thumb_start(1);set(control.mousetype, moveto);" >
<events onviewchange="if(view.vlookat GT 90,set(view.vlookat,90););if(view.vlookat LT -90,set(view.vlookat,-90););" /><view fovtype="MFOV" fov="120" hlookat="0" fovmin="90" fovmax="120" limitview="range" hlookatmin="-180" hlookatmax="180" vlookatmin="-90" vlookatmax="90" />
<preview url="%SWFPATH%/images/scene1/preview.jpg" />
<image type="CUBE"  progressive="false">
<left url="%SWFPATH%/images/scene1/pano_left.jpg" />
<front url="%SWFPATH%/images/scene1/pano_front.jpg" />
<right url="%SWFPATH%/images/scene1/pano_right.jpg" />
<back url="%SWFPATH%/images/scene1/pano_back.jpg" />
<up url="%SWFPATH%/images/scene1/pano_up.jpg" />
<down url="%SWFPATH%/images/scene1/pano_down.jpg" />
<mobile>
<left url="%SWFPATH%/images/scene1/mb_left.jpg" />
<front url="%SWFPATH%/images/scene1/mb_front.jpg" />
<right url="%SWFPATH%/images/scene1/mb_right.jpg" />
<back url="%SWFPATH%/images/scene1/mb_back.jpg" />
<up url="%SWFPATH%/images/scene1/mb_up.jpg" />
<down url="%SWFPATH%/images/scene1/mb_down.jpg" />
</mobile>
<tablet>
<left url="%SWFPATH%/images/scene1/tb_left.jpg" />
<front url="%SWFPATH%/images/scene1/tb_front.jpg" />
<right url="%SWFPATH%/images/scene1/tb_right.jpg" />
<back url="%SWFPATH%/images/scene1/tb_back.jpg" />
<up url="%SWFPATH%/images/scene1/tb_up.jpg" />
<down url="%SWFPATH%/images/scene1/tb_down.jpg" />
</tablet>
</image>
<hotspot name="spot1" devices="all" url="%SWFPATH%/spot/1446487094CA8Llf.png" capture="False" enabled="True" visible="True" rotatewithview="False" distorted="True" smoothing="True" scale="0.5999999999999996" atv="' . (rand(-500, 500)) . '" ath="' . (rand(-500, 500)) . '" rz="0" ry="0" rx="0" onclick="openurl(' . urldecode($_GET['siteroot']) . 'app/index.php?i=' . $_GET['i'] . '&amp;pno=' . $_GET['pno'] . '&amp;c=entry&amp;do=award&amp;m=wxz_quanjing,_blank);" onhover="set(hotspot[spot1info].visible,true);" onover="" onout="" />
<hotspot name="spot1info" devices="all" url="%SWFPATH%/plugins/textfield.swf" ath="89" atv="100" width="200" height="50" html="我是宝箱，快打开！" css="color:#333333; font-family:微软雅黑; font-size:14px; line-height:28px;" textshadow="0" visible="false" enabled="true" background="true" backgroundcolor="0xFFFFFF" backgroundalpha="0.5" border="true" ox="20" oy="-40" bordercolor="0xFFFFFF" borderalpha="0.5" borderwidth="5.0" roundedge="5" shadow="2" shadowrange="2.0" shadowangle="45" shadowcolor="0x000000" shadowalpha="0.5" autoheight="true" align="left" edge="left" onout="set(hotspot[spot1info].visible,false);" />
<action name="startscene">
</action>
</scene>
<contextmenu fullscreen="true" enterfs="全屏" exitfs="退出全屏">
<item caption="旋转开启/暂停" onclick="action(autogochange);" separator="true" />
<item caption="自动切换开启/暂停" onclick="action(autonextchange);"/>
</contextmenu>
<plugin name="ui307" url="%SWFPATH%/ui/1446496263mLdRAa.png" zorder="100" alpha="0.8" align="leftbottom" x="20" y="65" ox="0" oy="0" width="" height="" scale="1" visible="True" enabled="True" capture="True" keep="True" showpic="" onclick="ifnot(uishowpic === null , set(plugin[get(uishowpic)].y,-8000););set(uishowpic,uishowpic307);set(plugin[uishowpic307].y,0);tween(plugin[uishowpic307].alpha,1,0.1);" onhover="" />
<plugin type="image" name="uishowpic307" url="%SWFPATH%/showpic/1446498593ZQeTis.png" zorder="1000" alpha="1.0" blendmode="NORMAL" align="center" edge="center" x="0" y="-8000" width="" height="" scale="1" smoothing="True" visible="True" enabled="True" capture="False" handcursor="True" keep="True" children="True" preload="false" scalechildren="True" onclick="tween(alpha,0,0.1);tween(y,-8000);" effect="glow(0xffffff,0.5,10,100);" />
<plugin name="ui308" url="%SWFPATH%/ui/1446496249stEUWG.png" zorder="100" alpha="0.8" align="bottom" x="0" y="65" ox="0" oy="0" width="" height="" scale="1" visible="True" enabled="True" capture="True" keep="True" showpic="" onclick="openurl(' . urldecode($_GET['siteroot']) . 'app/index.php?i=' . $_GET['i'] . '&amp;pno=' . $_GET['pno'] . '&amp;c=entry&amp;do=win&amp;m=wxz_quanjing,_blank);" onhover="" />
<plugin name="ui309" url="%SWFPATH%/ui/1446496235PrVGc0.png" zorder="100" alpha="0.8" align="rightbottom" x="10" y="65" ox="0" oy="0" width="" height="" scale="1" visible="True" enabled="True" capture="True" keep="True" showpic="" onclick="ifnot(uishowpic === null , set(plugin[get(uishowpic)].y,-8000););set(uishowpic,uishowpic309);set(plugin[uishowpic309].y,0);tween(plugin[uishowpic309].alpha,1,0.1);" onhover="" />
<plugin type="image" name="uishowpic309" url="%SWFPATH%/showpic/1446496682Tuc2Yw.png" zorder="1000" alpha="1.0" blendmode="NORMAL" align="center" edge="center" x="0" y="-8000" width="" height="" scale="1" smoothing="True" visible="True" enabled="True" capture="False" handcursor="True" keep="True" children="True" preload="false" scalechildren="True" onclick="tween(alpha,0,0.1);tween(y,-8000);" effect="glow(0xffffff,0.11,10,100);" />
<plugin name="ui310" url="%SWFPATH%/ui/1446498065z0nkqD.png" zorder="100" alpha="1" align="bottom" x="0" y="7" ox="0" oy="0" width="" height="" scale="1" visible="True" enabled="True" capture="True" keep="True" showpic="" onclick="openurl(tel:13956993061,_blank);" onhover="" />
<plugin name="ui311" url="%SWFPATH%/ui/1446480271IGFbwF.png" zorder="100" alpha="1" align="righttop" x="0" y="0" ox="0" oy="0" width="" height="" scale="1" visible="True" enabled="True" capture="True" keep="True" showpic="" onclick="openurl(' . urldecode($_GET['siteroot']) . 'app/index.php?i=' . $_GET['i'] . '&amp;pno=' . ($_GET['pno']+1) . '&amp;c=entry&amp;do=quanjing&amp;m=wxz_quanjing,_blank);" onhover="" />
<action name="prevscene">
		copy(sceneindex, scene[get(xml.scene)].index);
		sub(lastindex, scene.count, 1);
		dec(sceneindex, 1, 0, get(lastindex));
		loadscene(get(scene[get(sceneindex)].name), null, MERGE, BLEND(0.5));
	</action>
	
	<action name="nextscene">
		copy(sceneindex, scene[get(xml.scene)].index);
		sub(lastindex, scene.count, 1);
		inc(sceneindex, 1, get(lastindex), 0);
		loadscene(get(scene[get(sceneindex)].name), null, MERGE, BLEND(0.5));
	</action>
<action name="autonextscene">
    if(skin_settings.openautonext == 1,
        nextscene();
        delayedcall(autonexttimer,15, autonextscene();
        );
    );
    </action>
    <action name="autonextchange">
    if(skin_settings.openautonext == 0,
        set(skin_settings.openautonext,1);
        set(autorotate.enabled,true);
        delayedcall(autonexttimer,15, autonextscene(););
        ,
        set(skin_settings.openautonext,0);
        stopdelayedcall(autonexttimer);
        set(plugin[closeautonext].visible,false);
    );
    </action>
    <action name="autogochange">
    if(autorotate.enabled == true,
    set(autorotate.enabled,false);,set(autorotate.enabled,true);
    );
    </action>
    
<action name="flyout">
copy(backup_rx,rx);copy(backup_ry,ry);copy(backup_rz,rz);copy(backup_scale,scale);copy(backup_directionalsound,directionalsound);copy(backup_zorder,zorder);
tween(rx, 0);tween(ry, 0);tween(rz, 0);tween(scale, 1.3);tween(flying, 1.0);set(directionalsound,false);set(zorder,100)
</action>
<action name="flyback">
tween(rx, get(backup_rx));tween(ry, get(backup_ry));tween(rz, get(backup_rz));
tween(scale, get(backup_scale));tween(flying, 0.0);set(directionalsound,get(backup_directionalsound));set(zorder,get(backup_zorder));
</action>
</krpano>';
