<?php

    require 'vendor/autoload.php';
    
    $mailChimp = new SlickLabs\MailChimp\MailChimp('ac4fda82ee346345a0ad557d3682d66a-us6');
    
    $args = [
        'timeout' => 20
    ];
    $response = $mailChimp->get("lists", $args);

    echo "<pre>"; print_r($response->getBody()); echo "</pre>";
    
?>