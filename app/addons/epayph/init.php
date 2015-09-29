<?php
if (!defined('BOOTSTRAP')) { die('Access denied'); }

fn_register_hooks(
    'get_checkout_payment_buttons',
    'payment_url',
    'update_payment_pre',
    'rma_update_details_post',
    'prepare_checkout_payment_methods'
);
