<?php

global $_W, $_GPC;
$do = (string) trim($_GPC['sdo']);

if ($do == 'defaultTpl') {
    $result = "";
    $tplid = $_GPC['id'];
    $info = pdo_get('wxz_easy_pay_post_tpl', "id='{$tplid}'");
    $result = array(
        'postage' => $info['postage'],
        'goodsPostNum' => $info['goodsPostNum'],
    );
    $info['desc'] = json_decode($info['desc'], true);
    $result['setpostageList'] = '';
    $i = 1;
    foreach ($info['desc'] as $city => $price) {
        $i++;
      
        $result['setpostageList'] .=
                <<<EOF
         <div class="setPrice-item">
                        <h4>第{$i}套地区与邮费</h4>
                        <ul class="message-list">
                            <li>
                                <div class="label-cell">地区</div>
                                <input type="text" value={$city} name="moreCity[]"  class="input-cell setArea moreCity" readonly="true" />
                                <!--<span class="dir-right"></span>-->
                            </li>
                            <li>
                                <div class="label-cell">邮费</div>
                                <input type="number" value="{$price}" name="morePostPrice[]" class="input-cell morePostPrice" placeholder="单位（元）" onkeyup="this.value = this.value.replace(/\D/g, '')" onafterpaste="this.value=this.value.replace(/\D/g,'')">
                            </li>
                        </ul>
                    </div>
EOF;
    }
    echo json_encode($result);
}
?>
