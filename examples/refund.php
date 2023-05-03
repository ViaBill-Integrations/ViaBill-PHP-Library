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

use App\Viabill\Exceptions\ViabillInvalidValueException;
use App\Viabill\Exceptions\ViabillRequestException;

$viabill = ViabillExample::initialize();

$order = ViabillExample::getOrderData();

$data = [
    // Transaction number isa a unique id for each apikey
    'id' => $order['transaction_id'], 
    'apikey' => $viabill->helper->getAPIKey(),
    'amount' => $order['amount'], // Total price to be refunded
    'currency' => $order['currency']
];

try {
    $response = $viabill->refundTransaction($data);
    if ($response) {
        $viabill->helper->displayUserMessage("The previous payment was refunded successfully!", 'notice', 'Payment refund');
    } else {
        $viabill->helper->displayUserMessage("Could not refund the previous payment. Make sure you have captured it first.", 'error', 'Payment refund');
    }
} catch (ViabillRequestException $e) {
    $viabill->helper->displayUserMessage($e->getMessage());
    return false;
}

