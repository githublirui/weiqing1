<?php

/**
 * test_demo模块定义
 *
 * @author sdfdasfdas
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Wxz_panoramaModule extends WeModule {

    public function fieldsFormDisplay($rid = 0) {
        //要嵌入规则编辑页的自定义内容，这里 $rid 为对应的规则编号，新增时为 0
    }

    public function fieldsFormValidate($rid = 0) {
        //规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
        return '';
    }

    public function fieldsFormSubmit($rid) {
        //规则验证无误保存入库时执行，这里应该进行自定义字段的保存。这里 $rid 为对应的规则编号
    }

    public function ruleDeleted($rid) {
        //删除规则时调用，这里 $rid 为对应的规则编号
    }

    public function settingsDisplay($settings) {
        global $_W, $_GPC;
        load()->func('tpl');
        //点击模块设置时将调用此方法呈现模块设置页面，$settings 为模块设置参数, 结构为数组。这个参数系统针对不同公众账号独立保存。
        //在此呈现页面中自行处理post请求并保存设置参数（通过使用$this->saveSettings()来实现）
        $settings = $this->module['config'];
        if (checksubmit()) {
            //字段验证, 并获得正确的数据$dat
            $data = array(
                'quanjin' => array(
                    'start_time' => $_GPC['start_time'],
                    'end_time' => $_GPC['end_time'],
                    'title' => $_GPC['title'],
                    'img' => $_GPC['img'],
                    'desc' => $_GPC['desc'],
                ),
                //活动名称
                'active_name' => $_GPC['active_name'],
                //强制关注
                'force_follow' => (int) $_GPC['force_follow'],
                'force_follow_url' => (string) $_GPC['force_follow_url'], //强制关注链接
            );
            if ($this->saveSettings($data)) {
                message('保存成功', 'refresh');
            }
        }
        include $this->template('setting');
        //这个操作被定义用来呈现 管理中心导航菜单
    }

}
