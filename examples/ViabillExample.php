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

use App\Viabill\Viabill;
use App\Viabill\SampleData;

/**
 * Provides sample data for the various examples
 */
class ViabillExample
{
           
    /**
     * @return Viabill
     *
     * Initializing Viabill Class for examples
     */
    public static function initialize() : Viabill
    {        
        return new Viabill();
    }    

    /**
     * @param  bool $renew_order_data
     * @return array
     * 
     * Get the example order data
     */
    public static function getOrderData($renew_order_data = false)
    {
        $sample_order = SampleData::getSampleOrderData($renew_order_data);

        $order = [
            // the order ID
            'id' => $sample_order['id'],
            // the transaction ID
            'transaction_id' => $sample_order['transaction_id'],
            // the amount of the order
            'amount' => $sample_order['amount'],
            // the currency of the order
            'currency' => $sample_order['currency'],
        ];

        return $order;
    }
}