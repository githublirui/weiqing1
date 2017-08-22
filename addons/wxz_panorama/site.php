<?php

defined('IN_IA') or exit('Access Denied');
define('WXZ_PANORAMA', IA_ROOT . '/addons/wxz_panorama');

class Wxz_panoramaModuleSite extends WeModuleSite {

    protected function auth() {
        global $_W, $_GPC;
        session_start();
        if (getip() == '127.0.0.1') {
            $_SESSION['__:proxy:openid'] = 'o5YC3t6MD1CjD2U_3dJQkMUjDQBA1';
        }
        if ($_GPC['openid']) {
            $sub_openid = $_GPC['openid'];
            $_SESSION['__:proxy:WXZ_PANORAMA_OPENID'] = $sub_openid;
        }

        $openid = $_SESSION['__:proxy:openid'];
        require_once WXZ_PANORAMA . '/source/Fans.class.php';
        $f = new Fans();
        if (!empty($openid)) {
            $exists = $f->getOne($openid, true);
            if (!empty($exists)) {
                return $exists;
            }
        }

        //查询appid和appsecret
        $api = $this->module['config']['api'];
        $callback = $_W['siteroot'] . "app/index.php?i={$_GPC['i']}&c=entry&do=auth&m={$_GPC['m']}";
        $callback = urlencode($callback);
        $state = $_SERVER['REQUEST_URI'];
        $stateKey = substr(md5($state), 0, 8);
        $_SESSION['__:proxy:forward'] = $state;

        $forward = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->module['config']['pay']['appid']}&redirect_uri={$callback}&response_type=code&scope=snsapi_userinfo&state={$stateKey}#wechat_redirect";
        header('Location: ' . $forward);
        exit;
    }

    public function doMobileAuth() {
        global $_GPC, $_W;
        //查询appid和appsecret
        session_start();
        $code = $_GPC['code'];
        load()->func('communication');
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$this->module['config']['pay']['appid']}&secret={$this->module['config']['pay']['appscret']}&code={$code}&grant_type=authorization_code";
        $resp = ihttp_get($url);
        if (is_error($resp)) {
            message('系统错误, 详情: ' . $resp['message']);
        }
        $auth = @json_decode($resp['content'], true);
        if (is_array($auth) && !empty($auth['openid'])) {
            $url = "https://api.weixin.qq.com/sns/userinfo?access_token={$auth['access_token']}&openid={$auth['openid']}&lang=zh_CN";
            $resp = ihttp_get($url);
            if (is_error($resp)) {
                message('系统错误');
            }
            $info = @json_decode($resp['content'], true);
            if (is_array($info) && !empty($info['openid'])) {
                $user = array();
                $user['uniacid'] = $_W['uniacid'];
                $user['openid'] = $info['openid'];
                $user['unionid'] = $info['unionid'];
                $user['nickname'] = $info['nickname'];
                $user['gender'] = $info['sex'];
                $user['city'] = $info['city'];
                $user['state'] = $info['province'];
                $user['avatar'] = $info['headimgurl'];
                $user['country'] = $info['country'];
                $user['client_ip'] = getip();

                if ($_GPC['openid']) {
                    $sub_openid = $_GPC['openid'];
                    $user['sub_openid'] = $sub_openid;
                }
                if (!empty($user['avatar'])) {
                    $user['avatar'] = rtrim($user['avatar'], '0');
                    $user['avatar'] .= '132';
                }

                require_once WXZ_PANORAMA . '/source/Fans.class.php';
                $f = new Fans();
                $f->save($user);

                $_SESSION['__:proxy:openid'] = $user['openid'];
                $forward = $_SESSION['__:proxy:forward'];
                header('Location: ' . $forward);
                exit();
            }
        }
        message('系统错误');
    }

    public function doMobileFrom() {
        global $_W, $_GPC;
        $yobyurl = "http://" . $_SERVER['HTTP_HOST'] . "/addons/wxz_parksys_fans";
        $attachurl = $_W['attachurl'];
        $settings = $this->module['config'];
        //这个操作被定义用来呈现 功能封面
        include $this->template('index');
    }

}

?>
