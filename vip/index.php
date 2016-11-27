<?php

    $MerchantID = 'eaea4ba0-84e0-11e6-a740-005056a205be';  //Required
    $Amount = 2000; //Amount will be based on Toman  - Required
    $Description = 'ارتقا ربات';  // Required
    $Email = 'klmclash13@gmail.com'; // Optional
    $Mobile = '09300322819'; // Optional
    $CallbackURL = 'http://www.codesupport.ir/vip/verify.php';  // Required

    // URL also can be ir.zarinpal.com or de.zarinpal.com
    $client = new SoapClient('https://www.zarinpal.com/pg/services/WebGate/wsdl', ['encoding' => 'UTF-8']);

    $result = $client->PaymentRequest([
        'MerchantID'     => $MerchantID,
        'Amount'         => $Amount,
        'Description'    => $Description,
        'Email'          => $Email,
        'Mobile'         => $Mobile,
        'CallbackURL'    => $CallbackURL,
    ]);

    //Redirect to URL You can do it also by creating a form
    if ($result->Status == 100) {
        header('Location: https://www.zarinpal.com/pg/StartPay/'.$result->Authority);
    } else {
        echo'ERR: '.$result->Status;
    }
