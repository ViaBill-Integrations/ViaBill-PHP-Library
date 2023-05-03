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
        $user_msg = implode('</li><li>', $response['messages']);
        $viabill->helper->displayUserMessage('<ul><li>'.$user_msg.'</li></ul>', 'notice', 'Notifications');
    } else {
        $viabill->helper->displayUserMessage('There are no new notifications', 'notice', 'Notifications');
    }
} catch (ViabillRequestException $e) {
    $viabill->helper->displayUserMessage($e->getMessage());
    return false;
}