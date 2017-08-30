<?php

/**
 * 模块处理程序
 *
 * @author lirui
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Wxz_panoramaModuleProcessor extends WeModuleProcessor {

    public function respond() {
        global $_W;
        //获取图片域名
        setting_load('remote');
        if ($_W['setting']['remote']['type']) {
            $attach_url = $_W['attachurl_remote'];
        } else {
            $attach_url = $_W['siteroot'] . $_W['config']['upload']['attachdir'];
        }

        $item = pdo_fetch("select * from " . tablename('wxz_panorama_reply_setting') . " where rid = " . $this->rule . " AND uniacid = " . $_W['uniacid']);
        $url = $_W['siteroot'] . 'app/index.php?i=' . $_W['account']['uniacid'] . '&c=entry&do=index&m=wxz_panorama&openid=' . urlencode($this->message['from']) . "&aid={$item['aid']}";

        $respon = array(
            'Title' => $item['title'],
            'Description' => $item['desc'],
            'PicUrl' => $attach_url . '/' . $item['img'],
            'Url' => $url,
        );
        return $this->respNews($respon);
    }

}
