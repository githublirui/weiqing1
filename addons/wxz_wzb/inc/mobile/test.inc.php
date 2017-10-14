<?php
global $_W,$_GPC;
load()->func('tpl');
$data['sort'] = 20;
pdo_update('wzbagent_live', $data, array('id'=>47));
?>