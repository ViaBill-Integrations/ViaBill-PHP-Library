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
    'id' => $order['transaction_id'], // Given Transaction id
    'apikey' => $viabill->helper->getAPIKey(),
    'currency' => $order['currency']
];

$response = null;

try {
    $response = $viabill->cancelTransaction($data);
    if ($response) {
        $viabill->helper->displayUserMessage("The previous payment was cancelled/voided successfully!", 'notice', 'Payment cancellation');
    } else {
        $viabill->helper->displayUserMessage("Could not cancel/void the previous payment. Make sure you have authorized it first, but not captured it or refunded it.", 'error', 'Payment cancellation');
    }
} catch (ViabillInvalidValueException $e) {
} catch (ViabillRequestException $e) {
    $viabill->helper->displayUserMessage($e->getMessage());
    return false;
}

// Response status should be 204
var_dump($response);