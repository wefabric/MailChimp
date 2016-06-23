# MailChimp #

Simple MailChimp API v3 wrapper for PHP. 
* Uses [Guzzle](http://docs.guzzlephp.org/) to make HTTP requests to the MailChimp API
* This means PSR-7 compliant response objects
* Able to set MailChimp API key on the fly

## Usage ##
Because the simple nature of this library you can call the [MailChimp API methods](http://developer.mailchimp.com/documentation/mailchimp/reference/overview/) by the desired HTTP request method.

First of all you have to include the MailChimp class.
```php
use SlickLabs\MailChimp\MailChimp;

$MailChimp = new MailChimp('MAILCHIMP-API-KEY');
```

For retrieving the MailChimp lists

```php
$response = $MailChimp->get("lists");

echo "<pre>"; print_r($response->getBody()); echo "</pre>";
```

Or adding a new subscriber to a list.

```php
$args = [
    "body" => [
        "email_address" => "myemail@email.com",
        "status" => "pending"
    ]
];

$response = $MailChimp->post("lists/LIST-ID/members", $args);

echo "<pre>"; print_r($response->getBody()); echo "</pre>";

```

Or updating an existing subscriber of a list.

```php
use SlickLabs\MailChimp\Subscriber;

$args = [
    "body" => [
        "status" => "subscribed"
    ]
];

$subscriberHash = Subscriber::hash("myemail@email.com");
$response = $MailChimp->put("lists/LIST-ID/members/".$subscriberHash, $args);

echo "<pre>"; print_r($response->getBody()); echo "</pre>";

```

Or deleting a subscriber of a list.

```php
use SlickLabs\MailChimp\Subscriber;

$subscriberHash = Subscriber::hash("myemail@email.com");
$response = $MailChimp->delete("lists/LIST-ID/members/".$subscriberHash);

echo "<pre>"; print_r($response->getBody()); echo "</pre>";

```

## Installation ##
Add MailChimp to your `composer.json` file. If you are not using [Composer](http://getcomposer.org), you should be. It's an excellent way to manage dependencies in your PHP application. 

```json
{  
  "require": {
    "slicklabs/mailchimp": "dev-master"
  }
}
```

Then at the top of your PHP script require the autoloader:

```bash
require 'vendor/autoload.php';
```