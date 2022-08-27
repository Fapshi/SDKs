<?php
// server.php
//
// Use this sample code to handle webhook events in your integration.
//
// 1) Paste this code into a new file (server.php)
//
// 2) Run the server on http://localhost:4242
//   php -S localhost:4242

require 'vendor/autoload.php';
include 'Fapshi.php';

$fapshi = new Fapshi();

$request_body = @file_get_contents('php://input');
$payload = json_decode($request_body);
// Get the transaction status from fapshi's API to be sure of its source
$event = $fapshi->payment_status($payload->{'transId'});
if($event['statusCode'] != 200){
    http_response_code(400);
    exit();
}

// Handle the event
switch ($event['status']) {
  case 'SUCCESSFUL':
    // Then define and call a function to handle a SUCCESSFUL payment
    echo 'successful - '+json_encode($event);
  case 'FAILED':
    // Then define and call a function to handle a FAILED payment
    echo 'failed - '+json_encode($event);
  case 'EXPIRED':
    // Then define and call a function to handle an expired transaction
    echo 'expired - '+json_encode($event);
  // ... handle other event types
  default:
    echo 'Unhandled event status:' . $event['status'];
}

http_response_code(200);