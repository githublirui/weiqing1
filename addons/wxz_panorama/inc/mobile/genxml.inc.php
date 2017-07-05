<?php

/**
 * 生成前端xml
 */
global $_W, $_GPC;
require_once WXZ_PANORAMA . '/source/Project.class.php';
require_once WXZ_PANORAMA . '/source/Scene.class.php';
require_once WXZ_PANORAMA . '/source/Hotspot.class.php';

//自定义参数
$krpanoVersion = '1.19'; //krpano 版本号
$html = ''; //xml配置
$krpanoElement = ''; //krpano内容
//参数校验
$pid = (int) $_GPC['pid'];
$next_project = Project::getNextProject($pid); //下一个项目

if (!$pid) {
    message('项目id错误');
}
$projectInfo = Project::getById($pid);

if (!$projectInfo) {
    message('项目不能存在,或已删除');
}

$projectConfigInfo = Project::getConfigById($pid);

//自动旋转
if ($projectConfigInfo['autorotate']) {
    $attributes = array(
        'enabled' => 'true',
    );
    $krpanoElement .= Scene::createTag('autorotate', $attributes);
}

$scenes = Scene::getScenesByProId($pid);

//添加actions
$element = "if(startscene === null, copy(startscene,scene[0].name));
        loadscene(get(startscene), null, MERGE);";
if ($projectConfigInfo['sound']) {
    $music = tomedia($projectConfigInfo['sound']);
    $element .="\r\n playsound(bggsnd, '{$music}', 10);";
}
//开始执行的action
$attributes = array(
    'name' => 'startup',
);
$krpanoElement .= Scene::createTag('action', $attributes, $element, true); //开始aaction
//皮肤
if ($projectConfigInfo['skin']) {
    $attributes = array(
        "url" => "%SWFPATH%/skin/{$projectConfigInfo['skin']}/vtourskin.xml",
    );
    $krpanoElement .= Scene::createTag('include', $attributes);
}

//自动旋转
if ($projectConfigInfo['autorotate']) {
    $attributes = array(
        "enabled" => 'true',
    );
    $krpanoElement .= Scene::createTag('autorotate', $attributes);
}

$treasureNum = $projectConfigInfo['treasure_num']; //宝藏数量
$sceneNum = count($scenes); //场景数量
$sceneTreasure = Scene::distribTreasure($sceneNum, $treasureNum);

//场景开始
foreach ($scenes as $i => $scene) {
    //view
    $sceneElement = '';
    $attributes = array(
        'hlookat' => '0',
        'vlookat' => '0',
        'fovtype' => 'MFOV',
        'fov' => '120',
        'maxpixelzoom' => '2.0',
        'fovmin' => '70',
        'fovmax' => '140',
        'limitview' => 'auto',
    );
    $viewElement = Scene::createTag('view', $attributes);

    //preview
    if ($scene['preview']) {
        $attributes = array(
            'url' => tomedia($scene['preview']),
        );
        $previewElement = Scene::createTag('preview', $attributes);
    }

    $imageElement = Scene::createSceneImage($scene);
    $sceneElement = $viewElement . "\r\n" . $previewElement . $imageElement; //场景元素内容
    //
    //随机插入宝藏
    if (isset($sceneTreasure[$i])) {
        for ($k = 0; $k < $sceneTreasure[$i]; $k++) {
            $attributes = array(
                'name' => "spot_treasure{$k}",
                'devices' => "all",
                'capture' => "False",
                'enabled' => "True",
                'visible' => "True",
                'rotatewithview' => "False",
                'distorted' => "True",
                'smoothing' => "True",
                'scale' => "0.5999999999999996",
                'atv' => rand(-500, 500),
                'ath' => rand(-500, 500),
                'onover' => "",
                'onout' => "",
                'rz' => "",
                'ry' => "",
                'rx' => "",
                'onclick' => "openurl(" . urldecode($_W['siteroot']) . "app/index.php?i=" . $_W['uniacid'] . "&amp;pid=" . $pid . "&amp;c=entry&amp;do=award&amp;m=" . $_GPC['m'] . ",_blank);",
                'url' => tomedia($projectConfigInfo['treasure']),
            );
            $sceneElement .= Scene::createTag('hotspot', $attributes);
        }
    }

    //生成热点
    $hotspotElement = Hotspot::createHotspotXml($scene, $next_project);
    $sceneElement .= $hotspotElement;
    //
    //
    //生成scene
    $attributes = array(
        'name' => "scene{$scene['id']}",
        'title' => $scene['name'],
        'onstart' => '',
        'lat' => '',
        'lng' => '',
        'heading' => '',
    );
    //缩略图
    if ($scene['thumb']) {
        $attributes['thumburl'] = tomedia($scene['thumb']);
    }
    $krpanoElement .= Scene::createTag('scene', $attributes, $sceneElement, true);
}
//场景结束
//
//背景音乐
if ($projectConfigInfo['sound']) {
    $attributes = array(
        "name" => "soundinterface",
        "url" => "%SWFPATH%/plugins/soundinterface.swf",
        "alturl" => "%SWFPATH%/plugins/soundinterface.js",
        "rootpath" => "",
        "preload" => "true",
        "keep" => "true",
        "volume" => "1.0",
    );
    $soundPlugin = Scene::createTag('plugin', $attributes);

    $attributes = array(
        "name" => "sndbt",
        "url" => "%SWFPATH%/images/soundonoff.png",
        "align" => "righttop",
        "x" => "10",
        "y" => "10",
        "keep" => "true",
        "scale" => "0.65",
        "onover" => "tween(alpha,1);",
        "onout" => "tween(alpha,0.45);",
        "crop" => "0|0|50|50",
        "onclick" => "switch(soundinterface.mute); switch(crop, 0|0|50|50, 0|50|50|50);",
    );
    $soundPlugin .= Scene::createTag('plugin', $attributes);

    $krpanoElement.= $soundPlugin;
}

//添加特效xml
if ($projectConfigInfo['effect']) {
    $krpanoElement .= Scene::getEffectXml($projectConfigInfo['effect']);
}

if ($projectConfigInfo['config']['bottombanner'] == 1) {
//底部三个按钮
    $krpanoElement .= '<plugin name="ui309" url="%SWFPATH%/ui/1446496235PrVGc0.png" zorder="100" alpha="0.8" align="rightbottom" x="10" y="65" ox="0" oy="0" width="" height="" scale="1" visible="True" enabled="True" capture="True" keep="True" showpic="" onclick="ifnot(uishowpic === null , set(plugin[get(uishowpic)].y,-8000););set(uishowpic,uishowpic309);set(plugin[uishowpic309].y,0);tween(plugin[uishowpic309].alpha,1,0.1);" onhover="" />';
    $krpanoElement .= '<plugin name="ui308" url="%SWFPATH%/ui/1446496249stEUWG.png" zorder="100" alpha="0.8" align="bottom" x="0" y="65" ox="0" oy="0" width="" height="" scale="1" visible="True" enabled="True" capture="True" keep="True" showpic="" onclick="openurl(' . urldecode($_W['siteroot']) . 'app/index.php?i=' . $_GET['i'] . '&amp;c=entry&amp;do=win&amp;m=wxz_panorama,_blank);" onhover="" />';
    $krpanoElement .= '<plugin name="ui307" url="%SWFPATH%/ui/1446496263mLdRAa.png" zorder="100" alpha="0.8" align="leftbottom" x="20" y="65" ox="0" oy="0" width="" height="" scale="1" visible="True" enabled="True" capture="True" keep="True" showpic="" onclick="ifnot(uishowpic === null , set(plugin[get(uishowpic)].y,-8000););set(uishowpic,uishowpic307);set(plugin[uishowpic307].y,0);tween(plugin[uishowpic307].alpha,1,0.1);" onhover="" />';
    $krpanoElement .= '<plugin type="image" name="uishowpic309" url="%SWFPATH%/showpic/1446496682Tuc2Yw.png" zorder="1000" alpha="1.0" blendmode="NORMAL" align="center" edge="center" x="0" y="-8000" width="" height="" scale="1" smoothing="True" visible="True" enabled="True" capture="False" handcursor="True" keep="True" children="True" preload="false" scalechildren="True" onclick="tween(alpha,0,0.1);tween(y,-8000);" effect="glow(0xffffff,0.11,10,100);" />';
    $krpanoElement .= '<plugin type="image" name="uishowpic307" url="%SWFPATH%/showpic/1446498593ZQeTis.png" zorder="1000" alpha="1.0" blendmode="NORMAL" align="center" edge="center" x="0" y="-8000" width="" height="" scale="1" smoothing="True" visible="True" enabled="True" capture="False" handcursor="True" keep="True" children="True" preload="false" scalechildren="True" onclick="tween(alpha,0,0.1);tween(y,-8000);" effect="glow(0xffffff,0.5,10,100);" />';
}
//krpano 属性
$attributes = array(
    'version' => $krpanoVersion,
    'title' => $projectInfo['name'],
    'onstart' => 'startup();',
    'logkey' => 'false',
    'showerrors' => 'false',
    'debugmode' => 'false',
);
//调试模式
if ($_W['config']['setting']['development'] || $_GPC['debug'] == 1) {
    $attributes['logkey'] = 'true';
    $attributes['showerrors'] = 'true';
    $attributes['debugmode'] = 'true';
}

//兼容手机
$attributes['devices'] = 'mobile';
$attributes['stagescale'] = 'calc:stagescale*(0.5 + 0.5*(stagescale LT 0.5))';

$html = Scene::createTag('krpano', $attributes, $krpanoElement, true);

include $this->template('scene/krpano');
