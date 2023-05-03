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

use App\Viabill\Viabill;
 
$viabill = new Viabill();
 
$index_url = $viabill->helper->getBaseURL(); 

$type = (isset($_REQUEST['type']))?$_REQUEST['type']:'';
$page_title = (isset($_REQUEST['page_title']))?$_REQUEST['page_title']:'';
$msg_header = (isset($_REQUEST['msg_header']))?$_REQUEST['msg_header']:'';
$msg_content = (isset($_REQUEST['msg_content']))?$_REQUEST['msg_content']:'';

switch ($type) {
    case 'error':
        if (empty($msg_header)) $msg_header = "Error(s)";
        $body = '<h3>'.$msg_header.'</h3>
            <div class="p-3 mb-2 bg-danger text-white">'.$msg_content.'</div>';
        break;
    case 'warning':        
        if (empty($msg_header)) $msg_header = "Warning(s)";
        $body = '<h3>'.$msg_header.'</h3>
            <div class="p-3 mb-2 bg-warning text-black">'.$msg_content.'</div>';
        break;
    case 'notice':
        if (empty($msg_header)) $msg_header = "Notice(s)";
        $body = '<h3>'.$msg_header.'</h3>
            <div class="p-3 mb-2 bg-primary text-black">'.$msg_content.'</div>'.
            '<a href="'.$index_url.'">Go back to main menu to try something difference</a>';
        break;    
    case '':    
}

$contents = '<!DOCTYPE html>
<html lang="en-gb" dir="ltr">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="utf-8" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-3">'.$body.'</div>    
</body>
</html>';

echo $contents;
