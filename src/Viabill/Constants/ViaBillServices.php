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

namespace App\Viabill\Constants;

class ViaBillServices
{
    // TODO: 
    // You must give a valid addon name, please contact tech@viabill.com    
    const ADDON_NAME = 'test';

    // These endpoints contain references to the addon name
    const API_END_POINTS = [        
        'myviabill'           => [
            'endpoint'        => '/api/addon/ADDON_NAME/myviabill',
            'method'          => 'GET',
            'required_fields' => [ 'key', 'signature' ],
            'optional_fields' => [],
            'status_codes'    => [
                200 => 'messages.viabillApiMessages.successfulRequest',
                400 => 'messages.viabillApiMessages.requestError',
                500 => 'messages.viabillApiMessages.apiServerError',
            ],
            'signature'       => '{key}#{secret}',
        ],
        'notifications'       => [
            'endpoint'        => '/api/addon/ADDON_NAME/notifications',
            'method'          => 'GET',
            'required_fields' => [ 'key', 'signature' ],
            'optional_fields' => [ 'platform', 'platform_ver', 'module_ver'],
            'status_codes'    => [
                200 => 'messages.viabillApiMessages.successfulRequest',
                400 => 'messages.viabillApiMessages.requestError',
                500 => 'messages.viabillApiMessages.apiServerError',
            ],
            'signature'       => '{key}#{secret}',
        ],
        'checkout'            => [
            'endpoint'        => '/api/checkout-authorize/addon/ADDON_NAME',
            'method'          => 'POST',
            'required_fields' => [
                'protocol',
                'apikey',
                'transaction',
                'order_number',
                'amount',
                'currency',
                'success_url',
                'cancel_url',
                'callback_url',
                'test',
                'md5check',
            ],
            'optional_fields' => [ 'customParams' ],
            'md5check'        => '{apikey}#{amount}#{currency}#{transaction}#{order_number}#{success_url}#{cancel_url}#{secret}',
            'status_codes'    => [
                200 => 'messages.viabillApiMessages.successfulRequest',
                204 => 'messages.viabillApiMessages.noContentResponse',
                301 => 'messages.viabillApiMessages.permanentRedirect',
                302 => 'messages.viabillApiMessages.temporaryRedirect',
                400 => 'messages.viabillApiMessages.requestError',
                403 => 'messages.viabillApiMessages.debtorCreditError',
                409 => 'messages.viabillApiMessages.requestFrequencyError',
                500 => 'messages.viabillApiMessages.apiServerError',
            ],
        ],
        'capture_transaction' => [
            'endpoint'        => '/api/transaction/capture',
            'method'          => 'POST',
            'required_fields' => [ 'id', 'apikey', 'signature', 'amount', 'currency' ],
            'optional_fields' => [],
            'status_codes'    => [
                200 => 'messages.viabillApiMessages.successfulRequest',
                204 => 'messages.viabillApiMessages.noContentResponse',
                400 => 'messages.viabillApiMessages.requestError',
                403 => 'messages.viabillApiMessages.debtorCreditError',
                409 => 'messages.viabillApiMessages.requestFrequencyError',
                500 => 'messages.viabillApiMessages.apiServerError',
            ],
            'signature'       => '{id}#{apikey}#{amount}#{currency}#{secret}',
        ],
        'cancel_transaction'  => [
            'endpoint'        => '/api/transaction/cancel',
            'method'          => 'POST',
            'required_fields' => [ 'id', 'apikey', 'signature' ],
            'optional_fields' => [],
            'status_codes'    => [
                200 => 'messages.viabillApiMessages.successfulRequest',
                204 => 'messages.viabillApiMessages.noContentResponse',
                400 => 'messages.viabillApiMessages.requestError',
                500 => 'messages.viabillApiMessages.apiServerError',
            ],
            'signature'       => '{id}#{apikey}#{secret}',
        ],
        'refund_transaction'  => [
            'endpoint'        => '/api/transaction/refund',
            'method'          => 'POST',
            'required_fields' => [ 'id', 'apikey', 'signature', 'amount', 'currency' ],
            'optional_fields' => [],
            'status_codes'    => [
                200 => 'messages.viabillApiMessages.successfulRequest',
                204 => 'messages.viabillApiMessages.noContentResponse',
                400 => 'messages.viabillApiMessages.requestError',
                403 => 'messages.viabillApiMessages.spxAccountInactive',
                500 => 'messages.viabillApiMessages.apiServerError',
            ],
            'signature'       => '{id}#{apikey}#{amount}#{currency}#{secret}',
        ],
        'renew_transaction'   => [
            'endpoint'        => '/api/transaction/renew',
            'method'          => 'POST',
            'required_fields' => [ 'id', 'apikey', 'signature' ],
            'optional_fields' => [],
            'status_codes'    => [
                200 => 'messages.viabillApiMessages.successfulRequest',
                204 => 'messages.viabillApiMessages.noContentResponse',
                400 => 'messages.viabillApiMessages.requestError',
                403 => 'messages.viabillApiMessages.debtorCreditError',
                500 => 'messages.viabillApiMessages.apiServerError',
            ],
            'signature'       => '{id}#{apikey}#{secret}',
        ],
        'transaction_status'  => [
            'endpoint'        => '/api/transaction/status',
            'method'          => 'GET',
            'required_fields' => [ 'id', 'apikey', 'signature' ],
            'optional_fields' => [],
            'status_codes'    => [
                200 => 'messages.viabillApiMessages.successfulRequest',
                204 => 'messages.viabillApiMessages.noContentResponse',
                400 => 'messages.viabillApiMessages.requestError',
                500 => 'messages.viabillApiMessages.apiServerError',
            ],
            'signature'       => '{id}#{apikey}#{secret}',
        ],
    ];

    public static function getApiEndPoint($end_point)
    {
        // check if the default ADDON name is still used
        $addon_name = self::ADDON_NAME;
        if ($addon_name == 'your-addon-name') {            
            $addon_name = 'test';
        }

        if (isset(self::API_END_POINTS[$end_point])) {
            $end_point_settings = self::API_END_POINTS[$end_point];

            $end_point_settings['endpoint'] = str_replace(
                'ADDON_NAME',
                $addon_name,
                $end_point_settings['endpoint']
            );
            return $end_point_settings;
        } else {
            exit("Unknown API End Point: $end_point");
        }

        return false;
    }
}
