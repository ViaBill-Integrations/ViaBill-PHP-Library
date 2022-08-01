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

namespace App\Viabill;

use App\Viabill\ViabillHelper;
use App\Viabill\Viabill;

class IncomingRequests
{
    /**     
     * @var ViabillHelper
     */
    private $helper;

    /**
     * RequestProcessor constructor.
     *
     * @param Database $db
     * @param bool     $testMode
     */
    public function __construct()
    {
        $this->helper = new ViabillHelper();
    }   
   
    /**
     * This function is handling the "success" request that is invoked by the Viabill server,
     * if the buyer has completed the payment during the checkout process.
     * It should redirect the buyer into a "Thank you for your payment" page.
     * 
     * @return HTTP Redirect
     */
    public function checkoutSuccess()
    {        
        try {
            $data = $this->getServerData('success');
        } catch (Exception $e) {
            $code = $e->getCode();
            $content = "An error has occured during the success call: ".$code;
            return $this->helper->httpResponse($content, $code);
        }

        $transaction_id = $data['transaction'] ?? null;
        $order_id = $data['orderNumber'] ?? null; 

        // TODO: 
        // You need to redirect the buyer to a "thank you page"
        // or something similar, based on the outcome of the checkout process
        // and the callback status, if available (i.e. APPROVED, REJECTED, CANCELLED)
        $redirect_url = $this->helper->getCheckoutRedirectURL('success', $transaction_id, $order_id);

        return $this->helper->httpRedirect($redirect_url);
    }

    /**
     * This function is handling the "cancel" request that is invoked by the Viabill server,
     * if the buyer has cancelled the payment during the checkout process.
     * It should redirect the buyer into a "The payment has been cancelled" page.
     * 
     * @return HTTP Response
     */
    public function checkoutCancel()
    {        
        try {
            $data = $this->getServerData('cancel');
        } catch (Exception $e) {
            $code = $e->getCode();
            $content = "An error has occured during the cancel call: ".$code;
            return $this->helper->httpResponse($content, $code);
        }

        $transaction_id = $data['transaction'] ?? null;
        $order_id = $data['orderNumber'] ?? null;

        // TODO: 
        // You need to redirect the buyer to a "order is cancelled page"
        // or something similar
        $redirect_url = $this->helper->getCheckoutRedirectURL('cancel', $transaction_id, $order_id);

        return $this->helper->httpRedirect($redirect_url); 
    }

    /**
     * This function is handling the "callback" request that is invoked by the Viabill server,
     * after the checkout process has been completed.
     * You should use this function to update the status of the order, based on the callback status
     * that can APPROVED, CANCELLED or REJECTED.
     * 
     * @return HTTP Response
     */
    public function checkoutCallback()
    {        
        try {
            $data = $this->getServerData('callback');
        } catch (Exception $e) {
            $code = $e->getCode();
            $content = "An error has occured during the callback call: ".$code;
            return response($content, $code);
        }

        $transaction_id = $data['transaction'] ?? null;
        $order_id = $data['orderNumber'] ?? null;                
        $amount = $data['amount'] ?? null;
        $currency = $data['currency'] ?? null;
        
        $log_msg = ''; 

        // Set the ViaBill API Key and Secret for the shop_id
        $viabill = new Viabill();        
        // Verify the callback signature
        if ($viabill->verifyCallbackSignature($data)) {
            $result_action = null;
            $capture = false;
            $callback_status = $data['status'];
            switch ($callback_status) {
            case 'APPROVED':
                $log_msg = 'SUCCESS: Transaction was approved';
                $result_action = 'approve';
                $transaction_type = $viabill->helper->getTransactionType($transaction_id);
                if ($transaction_type == 'sale') {
                    $captureData = [
                        'id' => $transaction_id,
                        'apikey' => $viabill->helper->getAPIKey(),
                        // amount must be negative
                        'amount' => ($this->helper->format_amount($amount) <= 0 ? $this->helper->format_amount($amount) : (-1 * abs($this->helper->format_amount($amount)))),
                        'currency' => $transaction['currency'],
                    ];
                    $capture = $viabill->captureTransaction($captureData);
                    if ($capture !== true) {
                        $log_msg = 'ERROR: Transaction was approved, but not captured';
                        $result_action = 'pending';
                    }
                }
                break;
            case 'CANCELLED':
                $log_msg = 'CANCELLED: Transaction was cancelled';
                $result_action = 'cancel';
                break;
            case'REJECTED':
                $log_msg = 'REJECTED: Transaction was rejected';
                $result_action = 'reject';
                break;
            default:
                $error_log_msg = 'ERROR: Unknown Viabill Server Callback Status:'.$callback_status;
                $result_action = 'cancel';
                break;
            }
            
            // Based on the callback status, you need to take some action
            // For instance, you may need to change the order status and
            // send a confirmation message to the buyer
            $this->helper->resolveCheckoutCallbackAction($result_action, $transaction_id, $order_id);

            // Send an HTTP 200 Response
            $content = '';
            $code = 200;
            return $this->helper->httpResponse($content, $code);
        } else {
            $log_msg = 'ERROR: Failed to verify callback signature.';            
        }

        if (!empty($log_msg)) {            
            $this->helper->log($log_msg);
        }

        $content = $msg ?? '';
        $code = 200;
        return $this->helper->httpResponse($content, $code);              
    }

    /**
     * TODO:
     * Get data from the Viabill server, after a call has been invoked ("success", "cancel" or "callback")
     * Note that each platform/framework will have a specific built-in function
     * to retrieve the request data in a safe and clean way. Use this instead of the following
     * "raw" method.
     * 
     * @param string $task
     * 
     * @return array
     */
    public function getServerData($task = null)
    {        
        $data = $_REQUEST;        

        return $data;
    }    

}
