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

require_once 'vendor/autoload.php';

use App\Viabill\Viabill;
use App\Viabill\IncomingRequests;

class PageContents
{
    /**
     * @var string
     */
    protected $contents;

    /**
     * @var string
     */
    protected $controller_name;

    /**
     * @var App\Viabill\Viabill
     */
    protected $viabill;

    public function __construct()
    {
        $this->viabill = new Viabill();
        if (isset($_REQUEST['controller'])) {
            $this->controller_name = $_REQUEST['controller'];
        }
    }    

    protected function routeIncomingRequest() 
    {
        $incoming = new IncomingRequests();        
        switch ($this->controller_name) {
        case 'success':
            $incoming->checkoutSuccess();
            break;
        case 'cancel':
            $incoming->checkoutCancel();
            break;
        case 'callback':
            $incoming->callback_callback();
            break;
        }
    }

    protected function getExamples()
    {
        $base_url = $this->viabill->helper->getBaseURL();

        $checkout_example_url = $base_url . 'examples/checkout.php';
        $myviabill_example_url = $base_url . 'examples/myviabill.php';
        $mynotifications_example_url = $base_url . 'examples/notifications.php';

        $contents = '<!DOCTYPE html>
<html lang="en-gb" dir="ltr">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="utf-8" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body>
    <div class="container">
    <h3>Welcome to Viabill - PHP Library</h3>
    <div class="p-3 mb-2 bg-primary text-white">Start your testing by placing a new order.</div>
    <ul>
    <li><a href="'.$checkout_example_url.'">Place a New Order</a></li>
    <li><a href="'.$myviabill_example_url.'">Visit MyViabill</a></li>
    <li><a href="'.$mynotifications_example_url.'">View Notifications</a></li>
    </ul>
    </div>
</body>
</html>';

        return $contents;
    }

    public function getContents()
    {
        if ($this->controller_name) {
            $this->routeIncomingRequest();
        } else {
            $contents = $this->getExamples();
            echo $contents;
        }                
    }
}

$page = new PageContents();
$page->getContents();

?>
