## PHP Library for Viabill Payments

This PHP library was built in order to help 3rd party developers and partners that want to write their own customized solution for handling Viabill payments. It can be used as a reference or as a base skeleton code.

## Setup and configuration

Before starting to run the various examples you need to run composer:

```sh
composer install
```

### Provide your Api Key and Secret Key

The values for these parameters are given by the tech support of Viabill (tech@viabill.com) or during the merchant registration process. Once you have the values for these parameters, edit the file src/Viabill/SampleData.php and set them appropriately:

```
const API_KEY = 'XXXXX'; // Your Api Key;
const SECRET_KEY = 'XXXXX'; // Your Secret Key
```
## Running the examples

Copy all the library files into a web server that meet the following requirements:
- Has a recent PHP version installed
- Is publically accessible 
- Supports the HTTPS protocol

Then, visit the following URL in your preferred browser:

```sh
https://your-server/library/
```
> Note: `http://your-server/library` hould be replaced with the actual library root folder.

If everything goes smoothly, you should see the welcome page:

![](/images/index.jpg)

Click on the "Place New Order" link to initiate the checkout process. The sample data for the order are generated randomly inside the src/Viabill/SampleData.php file.

## More configuration and ToDo items

The library code contains a lot of default values and selections in order to help you get started. Search for the `TODO:` keyphrase inside the files for more configuration options.

For instance, one of the constants found in src\Viabill\Constants\ViaBillServices.php is 
`const ADDON_NAME = 'test';`

Viabill's tech support will provide you with the addon name, in case you don't know that already.