<?php
/**
 * NOTICE OF LICENSE 
 *
 * @category  PHP
 * @package   Viabill_PHP_Library
 * @author    Viabill Addons <product@viabill.com>
 * @copyright 2022 Copyright © Viabill
 * @license   MIT License
 * @link      https://github.com/ViaBill-Integrations/ViaBill-PHP-Library
 *
 * @see /LICENSE
 *
 * International Registered Trademark & Property of Viabill 
 */

require_once '../vendor/autoload.php';
require_once 'ViabillExample.php';

use \App\Viabill\Exceptions\ViabillRequestException;

$viabill = ViabillExample::initialize();

$order = ViabillExample::getOrderData(true);

$data = [
    'apikey' => $viabill->helper->getAPIKey(),
    'transaction' => $order['transaction_id'],
    'order_number' => $order['id'],
    'amount' => $order['amount'],
    'currency' => $order['currency'],
    'success_url' => $viabill->helper->getSuccessURL($order),
    'cancel_url' => $viabill->helper->getCancelURL($order),
    'callback_url' => $viabill->helper->getCallbackURL($order),
    'test' => $viabill->helper->getTestMode()
];

$response = null;

try {
    $response = $viabill->checkout($data, []);
    if (isset($response['redirect_url'])) {
        // This URL is sent by the Viabill server
        // in order to redirect buyer/customer into the checkout page
        // on the Viabill server
        $redirect_url = $response['redirect_url'];
        return $viabill->helper->httpRedirect($redirect_url);
    }
} catch (ViabillRequestException $e) {
    $viabill->helper->displayUserMessage($e->getMessage());
    return false;
}