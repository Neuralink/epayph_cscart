<?php
use Tygh\Http;
use Tygh\Registry;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

// Return from epayph website
if (defined('PAYMENT_NOTIFICATION')) {
    if ($mode == 'return') {
        if (fn_check_payment_script('epayph.php', $_REQUEST['order_id'])) {
            $order_info = fn_get_order_info($_REQUEST['order_id'], true);
            fn_pp_save_mode($order_info);

            if ($order_info['status'] == 'O') {
                $edp_data = fn_generate_ekeys_for_edp(array('status_from' => STATUS_INCOMPLETED_ORDER, 'status_to' => 'O'), $order_info);
                fn_order_notification($order_info, $edp_data);
            }

            if (fn_allowed_for('MULTIVENDOR')) {
                if ($order_info['status'] == STATUS_PARENT_ORDER) {
                    $child_orders = db_get_hash_single_array("SELECT order_id, status FROM ?:orders WHERE parent_order_id = ?i", array('order_id', 'status'), $_REQUEST['order_id']);
                    foreach ($child_orders as $order_id => $order_status) {
                        if ($order_status == 'O') {
                            $order_info = fn_get_order_info($order_id, true);
                            $edp_data = fn_generate_ekeys_for_edp(array('status_from' => STATUS_INCOMPLETED_ORDER, 'status_to' => 'O'), $order_info);
                            fn_order_notification($order_info, $edp_data);
                        }
                    }
                }
            }
        }
        fn_order_placement_routines('route', $_REQUEST['order_id'], false);

    } elseif ($mode == 'cancel') {
        $order_info = fn_get_order_info($_REQUEST['order_id']);
        fn_pp_save_mode($order_info);

        $pp_response['order_status'] = 'N';
        $pp_response["reason_text"] = __('text_transaction_cancelled');

        if (!empty($_REQUEST['payer_email'])) {
            $pp_response['customer_email'] = $_REQUEST['payer_email'];
        }
        if (!empty($_REQUEST['payer_id'])) {
            $pp_response['client_id'] = $_REQUEST['payer_id'];
        }
        if (!empty($_REQUEST['memo'])) {
            $pp_response['customer_notes'] = $_REQUEST['memo'];
        }
        fn_finish_payment($_REQUEST['order_id'], $pp_response);
        fn_order_placement_routines('route', $_REQUEST['order_id']);
    }

} else {

    $epayph_account = $processor_data['processor_params']['account'];

    if ($processor_data['processor_params']['mode'] == 'test') {
        $epayph_url = "https://epay.ph/checkout/api/";
    } else {
        $epayph_url = "https://epay.ph/checkout/api/";
    }

    $epayph_currency = $processor_data['processor_params']['currency'];
    $epayph_item_name = $processor_data['processor_params']['item_name'];
    //Order Total
    $epayph_shipping = fn_order_shipping_cost($order_info);
    $epayph_total = fn_format_price($order_info['total'] - $epayph_shipping, $epayph_currency);
    $epayph_shipping = fn_format_price($epayph_shipping, $epayph_currency);
    $epayph_order_id = $processor_data['processor_params']['order_prefix'].(($order_info['repaid']) ? ($order_id .'_'. $order_info['repaid']) : $order_id);

    $_phone = preg_replace('/[^\d]/', '', $order_info['phone']);
    $_ph_a = $_ph_b = $_ph_c = '';

    if ($order_info['b_country'] == 'US') {
        $_phone = substr($_phone, -10);
        $_ph_a = substr($_phone, 0, 3);
        $_ph_b = substr($_phone, 3, 3);
        $_ph_c = substr($_phone, 6, 4);
    } elseif ($order_info['b_country'] == 'GB') {
        if ((strlen($_phone) == 11) && in_array(substr($_phone, 0, 2), array('01', '02', '07', '08'))) {
            $_ph_a = '44';
            $_ph_b = substr($_phone, 1);
        } elseif (substr($_phone, 0, 2) == '44') {
            $_ph_a = '44';
            $_ph_b = substr($_phone, 2);
        } else {
            $_ph_a = '44';
            $_ph_b = $_phone;
        }
    } elseif ($order_info['b_country'] == 'AU') {
        if ((strlen($_phone) == 10) && $_phone[0] == '0') {
            $_ph_a = '61';
            $_ph_b = substr($_phone, 1);
        } elseif (substr($_phone, 0, 2) == '61') {
            $_ph_a = '61';
            $_ph_b = substr($_phone, 2);
        } else {
            $_ph_a = '61';
            $_ph_b = $_phone;
        }
    } else {
        $_ph_a = substr($_phone, 0, 3);
        $_ph_b = substr($_phone, 3);
    }

    // US states
    if ($order_info['b_country'] == 'US') {
        $_b_state = $order_info['b_state'];
    // all other states
    } else {
        $_b_state = fn_get_state_name($order_info['b_state'], $order_info['b_country']);
    }

    $return_url = fn_url("payment_notification.return?payment=epayph&order_id=$order_id", AREA, 'current');
    $cancel_url = fn_url("payment_notification.cancel?payment=epayph&order_id=$order_id", AREA, 'current');
    $notify_url = fn_url("payment_notification.epayph_ipn", AREA, 'current');

    $post_data = array(
        'charset' => 'utf-8',
        'cmd' => '_cart',
        'custom' => $order_id,
        'invoice' => $epayph_order_id,
        'redirect_cmd' => '_xclick',
        'rm' => 2,
        'email' => $order_info['email'],
        'first_name' => $order_info['b_firstname'],
        'last_name' => $order_info['b_lastname'],
        'address1' => $order_info['b_address'],
        'address2' => $order_info['b_address_2'],
        'country' => $order_info['b_country'],
        'city' => $order_info['b_city'],
        'state' => $_b_state,
		'location' => $order_info['b_city'].' '.$_b_state.' '.$order_info['b_country'],
        'zip' => $order_info['b_zipcode'],
        'day_phone_a' => $_ph_a,
        'day_phone_b' => $_ph_b,
        'day_phone_c' => $_ph_c,
        'night_phone_a' => $_ph_a,
        'night_phone_b' => $_ph_b,
        'night_phone_c' => $_ph_c,
		'phone' => trim($_ph_a.' '.$_ph_b.' '.$_ph_c),
        'business' => $epayph_account,
        'item_name' => $epayph_item_name,
        //'amount' => $epayph_total,
        'upload' => '1',
        'currency_code' => $epayph_currency,
        'return' => $return_url,
        'cancel_return' => $cancel_url,
        'notify_url' => $notify_url,
        'shipping' => $epayph_shipping,
        'bn' => 'ST_ShoppingCart_Upload_US',
		'shipping_name' => $order_info['b_firstname'].' '.$order_info['b_lastname'],
		'shipping_address1' => $order_info['b_address'],
		'shipping_address2' => $order_info['b_address_2'],
		'shipping_location' => $order_info['b_city'].' '.$_b_state.' '.$order_info['b_country'],
		'shipping_city' => $order_info['b_city'],
		'shipping_state' => $_b_state,
		'shipping_zip' => $order_info['b_zipcode'],
		'shipping_country' => $order_info['b_country'],		
		'affiliate' => @$_COOKIE['affiliate']
    );

    list($products, $product_count) = fn_pp_standart_prepare_products($order_info, $epayph_currency);
    $post_data = array_merge($post_data, $products);

    if ($order_info['status'] == STATUS_INCOMPLETED_ORDER) {
        fn_change_order_status($order_id, 'O', '', false);
    }
    if (fn_allowed_for('MULTIVENDOR')) {
        if ($order_info['status'] == STATUS_PARENT_ORDER) {
            $child_orders = db_get_hash_single_array("SELECT order_id, status FROM ?:orders WHERE parent_order_id = ?i", array('order_id', 'status'), $order_id);

            foreach ($child_orders as $order_id => $order_status) {
                if ($order_status == STATUS_INCOMPLETED_ORDER) {
                    fn_change_order_status($order_id, 'O', '', false);
                }
            }
        }
    }

    fn_create_payment_form($epayph_url, $post_data, 'ePay.ph server', false);
}
exit;
