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
} catch (ViabillInvalidValueException $e) {
} catch (ViabillRequestException $e) {
    var_dump($e);
    return false;
}
// Response status should be 204
var_dump($response);