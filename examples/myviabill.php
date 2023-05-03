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
    $response = $viabill->myViabill($data);
    if (!empty($response['url'])) {
        $redirect_url = $response['url'];
        return $viabill->helper->httpRedirect($redirect_url);
    } else {
        $viabill->helper->displayUserMessage($response['error'], 'error');
    }
} catch (ViabillRequestException $e) {
    $viabill->helper->displayUserMessage($e->getMessage());
    return false;
}
