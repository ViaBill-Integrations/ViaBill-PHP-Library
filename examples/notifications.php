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

$data = [
    'key' => $viabill->helper->getAPIKey(),
    'secret' => $viabill->helper->getSecretKey()
];

$response = null;

try {
    $response = $viabill->notifications($data);
    if (!empty($response['messages'])) {
        var_dump($response['messages']);
    } else {
        exit("You have no notification messages!");
    }
} catch (ViabillRequestException $e) {
    var_dump($e);
    return false;
}