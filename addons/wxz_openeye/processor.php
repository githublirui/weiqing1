<?php

/**
 * 模块处理程序
 *
 * @author lirui
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Wxz_openeyeModuleProcessor extends WeModuleProcessor {

    public function respond() {
        global $_W;
        $setting = $this->module['config'];
        $content = $this->message['content'];
        $url = $_W['siteroot'] . 'app/index.php?i=' . $_W['account']['uniacid'] . '&c=entry&do=index&m=wxz_openeye&openid=' . urlencode($this->message['from']);
        $content = array(
            array(
                'title' => $setting['share']['title'],
                'description' => $setting['share']['desc'],
                'picurl' => $setting['share']['img'],
                'url' => $url,
            ),
        );
        return $this->respNews($content);
        //这里定义此模块进行消息处理时的具体过程, 请查看微擎文档来编写你的代码
    }

}
