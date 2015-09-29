<?php
use Tygh\Http;
use Tygh\Registry;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($mode == 'epayph_ipn') {
        if (!empty($_REQUEST['custom'])) {
            unset($_REQUEST['dispatch']);
            
            $result = '';
            $_REQUEST['cmd'] = '_notify-validate';
            $data = array_merge(array('cmd' => '_notify-validate'), $_REQUEST);
            //the txn_type variable absent in case of refund
            if (isset($data['txn_type']) && in_array($data['txn_type'], array('cart', 'express_checkout', 'web_accept')) || !isset($data['txn_type'])) {
                $order_ids = fn_pp_get_ipn_order_ids($data);
                $mode = fn_pp_get_mode(reset($order_ids));
                $url = 'https://epay.ph/api/validateIPN';
                $result = Http::post($url, $data);
            }

            if ($result == '{"return":"VERIFIED"}') {
                fn_define('ORDER_MANAGEMENT', true);
                foreach($order_ids as $order_id) {
                    fn_process_epayph_ipn($order_id, $data);
                }
            }
        }
        exit;
    }
}
