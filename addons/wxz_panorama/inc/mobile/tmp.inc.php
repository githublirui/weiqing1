<?php

$spotautokey = 1;

foreach ($hotspots as $spotautokey => $spotrow) {
    $spotrow = array_merge($spotrow, $spotrow['config']);
    unset($spotrow['config']);
    $xml .= "<hotspot name=\"spot$spotautokey\" ";
    $xml .= "devices=\"{$spotrow['devicetype']}\" ";
    $onclickstring = "";
    $onhoverstring = "";
    $onoverstring = "";
    $onoutstring = "";
    if ($spotrow['openshowspotname'] == 1 && $spotrow['action'] != 4 && $spotrow['openinfo'] == 0) {
        $onhoverstring .= "showtext('{$spotrow['name']}');";
    }
    if ($spotrow['action']) {
        if ($spotrow['action'] == 1 && $spotrow['target_scene'] != 0) {
            $movetoxml = "moveto({$spotrow['spoth']},{$spotrow['spotv']},smooth());";
            if ($spotrow['spottype'] == 1) {
                if ($spotrow['screenchange'] == 1) {
                    $onclickstring .= "ifnot(device.html5,moveto({$spotrow['spoth']},{$spotrow['spotv']},smooth());loadscene(scene{$spotrow['target_scene']}, view.vlookat={$spotrow['target_spotv']}&amp;view.hlookat={$spotrow['target_spoth']}, MERGE,ZOOMBLEND(1,1));,{$movetoxml}loadscene(scene{$spotrow['target_scene']}, view.hlookat={$spotrow['target_spoth']}, MERGE,ZOOMBLEND(1,1)););";
                } else if ($spotrow['screenchange'] == 2) {
                    $onclickstring .= "ifnot(device.html5,moveto({$spotrow['spoth']},{$spotrow['spotv']},smooth());loadscene(scene{$spotrow['target_scene']}, view.vlookat={$spotrow['target_spotv']}&amp;view.hlookat={$spotrow['target_spoth']}, MERGE,ZOOMBLEND(1,3));,{$movetoxml}loadscene(scene{$spotrow['target_scene']}, view.hlookat={$spotrow['target_spoth']}, MERGE,ZOOMBLEND(1,3)););";
                }
            } else {
                if ($spotrow['screenchange'] == 1) {
                    $onclickstring .= "ifnot(device.html5,loadscene(scene{$spotrow['target_scene']}, view.vlookat={$spotrow['target_spotv']}&amp;view.hlookat={$spotrow['target_spoth']}, MERGE,ZOOMBLEND(1,1));,{$movetoxml}loadscene(scene{$spotrow['target_scene']}, view.hlookat={$spotrow['target_spoth']}, MERGE,ZOOMBLEND(1,1)););";
                } else if ($spotrow['screenchange'] == 2) {
                    $onclickstring .= "ifnot(device.html5,loadscene(scene{$spotrow['target_scene']}, view.vlookat={$spotrow['target_spotv']}&amp;view.hlookat={$spotrow['target_spoth']}, MERGE,ZOOMBLEND(1,3));,{$movetoxml}loadscene(scene{$spotrow['target_scene']}, view.hlookat={$spotrow['target_spoth']}, MERGE,ZOOMBLEND(1,3)););";
                }
            }
        } else if ($spotrow['action'] == 2 && $spotrow['showpic'] != "") {
            if ($spotrow['showpictype'] == 1) {
                $onclickstring .= "ifnot(scene{$scene['id']}.showpic === null , set(plugin[get(scene{$scene['id']}.showpic)].y,-8000););set(scene{$scene['id']}.showpic,showpic{$spotrow['id']});set(plugin[showpic{$spotrow['id']}].y,0);tween(plugin[showpic{$spotrow['id']}].alpha,1,0.1);";
            } else if ($spotrow['showpictype'] == 2) {
                $onoverstring .= "ifnot(scene{$scene['id']}.showpic === null , set(plugin[get(scene{$scene['id']}.showpic)].y,-8000););set(scene{$scene['id']}.showpic,showpic{$spotrow['id']});set(plugin[showpic{$spotrow['id']}].y,0);tween(plugin[showpic{$spotrow['id']}].alpha,1,0.1);";
            }
        } else if ($spotrow['action'] == 3) {
            $http = str_replace("&", "&amp;", $spotrow['httplink']);
            $onclickstring .= "openurl($http,_blank);";
        } else if ($spotrow['action'] == 1 && $spotrow['target_project'] != 0) {
            //项目跳转
            $pid = $spotrow['target_project'];
            if ($pid == -1) {
                $pid = $next_project['id'];
            }
            $httplink = $this->createMobileUrl('quanjing', array('pid' => $pid));
            $http = str_replace("&", "&amp;", $httplink);
            $onclickstring .= "openurl($http,_blank);";
        }
    }
    if ($spotrow['openinfo'] == 1) {
        $onhoverstring .= "set(hotspot[spot{$spotautokey}info].visible,true);";
    }
    if ($spotrow['spottype'] == 1) {
        $spotname = tomedia($spotrow['img']);
        $xml .= "url=\"{$spotname}\" ";
        $xml .= "ath=\"{$spotrow['spoth']}\" atv=\"{$spotrow['spotv']}\" ";
        $xml .= "onclick=\"$onclickstring\" ";
        $xml .= "onhover=\"$onhoverstring\" ";
        $xml .= "onover=\"$onoverstring\" ";
        $xml .= "onout=\"$onoutstring\" ";
        $xml .= "/>\r\n";
    } else {
        $xml .= "/>\r\n";
    }
    if ($spotrow['openinfo'] == 1) {
        $xml .= "<hotspot name=\"spot{$spotautokey}info\" ";
        $xml .= "devices=\"{$spotrow['devicetype']}\" ";
        $xml .= "url=\"%SWFPATH%/plugins/textfield.swf\" ";
        if ($spotrow['spottype'] == 1) {
            $xml .= "ath=\"{$spotrow['spoth']}\" atv=\"{$spotrow['spotv']}\" ";
        }
        $xml .= "width=\"{$spotrow['infowidth']}\" height=\"50\" ";
        $textinfo = str_replace("<", "[", $spotrow['textinfo']);
        $textinfo = str_replace(">", "]", $textinfo);
        $textinfo = str_replace('"', "'", $textinfo);
        $xml .= "html=\"$textinfo\" ";
        $xml .= "css=\"color:#333333; font-family:微软雅黑; font-size:14px; line-height:28px;\" textshadow=\"0\" visible=\"false\" ";
        $xml .= "enabled=\"true\" background=\"true\" backgroundcolor=\"0xFFFFFF\" backgroundalpha=\"0.5\" border=\"true\" ox=\"20\" oy=\"-40\" ";
        $xml .= "bordercolor=\"0xFFFFFF\" borderalpha=\"0.5\" borderwidth=\"5.0\" roundedge=\"5\" shadow=\"2\" shadowrange=\"2.0\" ";
        $xml .= "shadowangle=\"45\" shadowcolor=\"0x000000\" shadowalpha=\"0.5\" ";
        $xml .= "autoheight=\"true\" align=\"left\" edge=\"left\" onout=\"set(hotspot[spot{$spotautokey}info].visible,false);\" ";
        $xml .= "/>\r\n";
    }
    if ($spotrow['action'] == 2 && $spotrow['showpic'] != "") {
        $spotrow['showpic'] = tomedia($spotrow['showpic']);
        if ($spotrow['showpictype'] == 1) {
            $xml .= "<plugin type=\"image\" name=\"showpic{$spotrow['id']}\" url=\"{$spotrow['showpic']}\" zorder=\"1000\" alpha=\"1.0\" blendmode=\"NORMAL\" align=\"center\" edge=\"center\" x=\"0\" y=\"-80000\" width=\"\" height=\"\" scale=\"1\" smoothing=\"True\" visible=\"True\" enabled=\"True\" capture=\"False\" handcursor=\"True\" keep=\"False\" children=\"True\" preload=\"false\" scalechildren=\"True\" onclick=\"tween(alpha,0,0.1);tween(y,-80000);\" effect=\"glow(0x{$spotrow['showpicbordercolor']},{$spotrow['showpicborderalpha']},{$spotrow['showpicborderwidth']},100);\" />\r\n";
        } else if ($spotrow['showpictype'] == 2) {
            $xml .= "<plugin type=\"image\" name=\"showpic{$spotrow['id']}\" url=\"{$spotrow['showpic']}\" zorder=\"1000\" alpha=\"1.0\" blendmode=\"NORMAL\" align=\"center\" edge=\"center\" x=\"0\" y=\"-80000\" width=\"\" height=\"\" scale=\"1\" smoothing=\"True\" visible=\"True\" enabled=\"True\" capture=\"False\" handcursor=\"True\" keep=\"False\" children=\"True\" preload=\"false\" scalechildren=\"True\" onout=\"tween(alpha,0,0.1);tween(y,-80000);\" effect=\"glow(0x{$spotrow['showpicbordercolor']},{$spotrow['showpicborderalpha']},{$spotrow['showpicborderwidth']},100);\" />\r\n";
        }
    }
}
