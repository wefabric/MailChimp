# MailChimp #
Created with the single purpose to communicate with the MailChimp API v3 as straight forward as possible with proper error handling.

* Uses [Guzzle](http://docs.guzzlephp.org/) to make HTTP requests to the MailChimp API
* Build for the future, PSR-1, PSR-2 & PSR-7 compliant
* Very flexible, easily extend the MailChimp class and create your own business logic
* Able to set MailChimp API key on the fly

## Usage ##
Because the simple nature of this library you can call the [MailChimp API methods](http://developer.mailchimp.com/documentation/mailchimp/reference/overview/) by the desired HTTP request method.

First of all you have to include the MailChimp class.
```php
require 'vendor/autoload.php';

use SlickLabs\MailChimp\MailChimp;
use SlickLabs\MailChimp\Exception\ResponseException;

$MailChimp = new MailChimp('MAILCHIMP-API-KEY');
```

For retrieving the MailChimp lists

```php
try {
    $response = $MailChimp->get("lists");
    echo '<pre>'; print_r($response->getBody()); echo '</pre>';
} catch(ResponseException $e) {
    echo $e->getStatusCode().'<br />';
    echo $e->getTitle().'<br />';
    echo $e->getType().'<br />';
    echo $e->getDetail().'<br />';
}
```

Or adding a new subscriber to a list.

```php
$args = [
    "body" => [
        "email_address" => "myemail@myemail.com",
        "status" => "pending"
    ]
];

try {
    $response = $MailChimp->post("lists/LIST-ID/members", $args);
    echo '<pre>'; print_r($response->getBody()); echo '</pre>';
} catch(ResponseException $e) {
    echo $e->getStatusCode().'<br />';
    echo $e->getTitle().'<br />';
    echo $e->getType().'<br />';
    echo $e->getDetail().'<br />';
}
```

Or updating an existing subscriber of a list.

```php
use SlickLabs\MailChimp\Subscriber;

$args = [
    "body" => [
        "status" => "subscribed"
    ]
];

$subscriberHash = Subscriber::hash("myemail@myemail.com");

try {
    $response = $MailChimp->put('lists/LIST-ID/members/'.$subscriberHash, $args);
    echo '<pre>'; print_r($response->getBody()); echo '</pre>';
} catch(ResponseException $e) {
    echo $e->getStatusCode().'<br />';
    echo $e->getTitle().'<br />';
    echo $e->getType().'<br />';
    echo $e->getDetail().'<br />';
}
```

Or deleting a subscriber of a list.

```php
use SlickLabs\MailChimp\Subscriber;

$subscriberHash = Subscriber::hash("myemail@email.com");
try {
    $response = $MailChimp->delete("lists/LIST-ID/members/".$subscriberHash);
    echo '<pre>'; print_r($response->getBody()); echo '</pre>';
} catch(ResponseException $e) {
    echo $e->getStatusCode().'<br />';
    echo $e->getTitle().'<br />';
    echo $e->getType().'<br />';
    echo $e->getDetail().'<br />';
}
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

## Contributing ##
This is a fairly simple wrapper, but it can be made much better by contributions from those using it. If you'd like to suggest an improvement, please raise an issue to discuss it before making your pull request.

Pull requests for bugs are more than welcome - please explain the bug you're trying to fix in the message.