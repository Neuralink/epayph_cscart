<?php
use Tygh\Registry;
use Tygh\Settings;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($mode == 'update' && $_REQUEST['addon'] == 'epayph' && (!empty($_REQUEST['pp_settings']) || !empty($_REQUEST['epayph_logo_image_data']))) {
        $pp_settings = isset($_REQUEST['pp_settings']) ? $_REQUEST['pp_settings'] : array();
        fn_update_epayph_settings($pp_settings);
    }
}

if ($mode == 'update') {
    if ($_REQUEST['addon'] == 'epayph') {
        Registry::get('view')->assign('pp_settings', fn_get_epayph_settings());
    }
}
