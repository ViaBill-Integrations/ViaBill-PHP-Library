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

$order = ViabillExample::getOrderData();

function generateRandomString($length) {
    $characters = 'abcdefghijklmnopqrstuvwxyz';
    $string = '';
    for ($i = 0; $i < $length; $i++) {
        $string .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $string;
}

function setInSampleData($param, $value)
{
    $from = null;
	$to = null;

	$sample_data_filename = __DIR__.'/../src/Viabill/SampleData.php';	
    $contents = file_get_contents($sample_data_filename);
	if (empty($contents)) {
		exit("Could not open file $sample_data_filename to set the user account registration parameters");
	}
    
    switch ($param) {
        case 'API_KEY':
            $from = "const API_KEY = 'XXXXX'";
            $to = "const API_KEY = '{$value}'";
            break;
        case 'SECRET_KEY':
            $from = "const SECRET_KEY = 'XXXXX'";
            $to = "const SECRET_KEY = '{$value}'";
            break;
        case 'PRICETAG_MERCHANT_ID':
            $from = "const PRICETAG_MERCHANT_ID = null";
            $to = "const PRICETAG_MERCHANT_ID = '{$value}'";
            break;    
        default:
            // do nothing
            exit("Error! Could not locate the parameter $param in the $sample_data_filename file");
            break;    
    }

    if (isset($from)) {
        $contents = str_replace($from, $to, $contents);
        file_put_contents($sample_data_filename, $contents);
    }
}

$rand_name = generateRandomString(8);

$merchant_email = $rand_name . '@notify.gr';
$merchant_name = ucfirst($rand_name);
$merchant_shop_url = 'https://www.'.$rand_name.'.com/shop';

$data = [
    'email' => $merchant_email, // the email of the merchant/Viabill user,
    'name' => $merchant_name, // The name of the merchant/Viabill user,
    'url' => $merchant_shop_url, // The URL of the merchant's shop
    'country' => 'dk', // The 2 letter country code, i.e. 'da' for Denmark, 'es' for Spain
    'affiliate' => 'WOOCOMMERCE' // the name of the platform. Contact Viabill support for details.    
];

try {
    //$response = $viabill->registerViabillUser($data);
    $response = [
        'key' => "eyJhbGciOiJIUzI1NiJ9.eyJyb2xlcyI6WyJNRVJDSEFOVCJdLCJ1dWlkIjoiMjQ2YjMxYzUtZGVjZC0xMWVkLTg4OTEtOTc4M2E5NTI1MTg4IiwidHYiOjAsImVudiI6IlRFU1QiLCJpYXQiOjE2ODE5MjA4MTcsImV4cCI6MTk5NzU0MDAxN30.A-lAOhviwKXQWucq3SEEiY5ViVE5Fqd6mzG6HVLtwf8",
        'secret' => "R2juXWT6WSAu",
        'pricetagScript' => "<script>(function(){var o=document.createElement('script');o.type='text/javascript';o.async=true;o.src='https://pricetag-test.viabill.com/script/ws7KfbLLS4k%3D';var s=document.getElementsByTagName('script')[0];s.parentNode.insertBefore(o,s);})();</script>"
    ];
    setInSampleData('API_KEY', $response['key']);
    setInSampleData('SECRET_KEY', $response['secret']);
    $pricetag = $response['pricetagScript'];
    $pos = strpos($pricetag, 'viabill.com/script/');
    if ($pos) {
        $end_pos = strpos($pricetag, "'", $pos+1);
        if ($end_pos) {
            $pricetag_merchant_id = substr($pricetag, $pos + strlen('viabill.com/script/'), $end_pos - $pos - strlen('viabill.com/script/'));
            setInSampleData('PRICETAG_MERCHANT_ID', $pricetag_merchant_id);
        }
    }

    $redirect_url = $viabill->helper->getBaseURL();
    return $viabill->helper->httpRedirect($redirect_url);        
} catch (ViabillInvalidValueException $e) {    
    $viabill->helper->displayUserMessage($e->getMessage());
    return false;
}


