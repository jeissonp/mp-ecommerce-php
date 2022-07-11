<?php
require __DIR__ .  '/vendor/autoload.php';

$json = file_get_contents('php://input');
error_log('execute webhook');
error_log('body');
error_log($json);
error_log('$_GET');
error_log(json_encode($_GET));

$result = [
  'status' => 'None',
  'approved' => false,
];

if (isset($_GET["topic"]) && $_GET["topic"] == 'payment') {
  MercadoPago\SDK::setAccessToken(getenv('MP_ACCESS_TOKEN'));


  $payment = \MercadoPago\Payment::find_by_id($_GET["id"]);
  $result['status'] = $payment->status;
  if ($payment->status == 'approved') {
    $result['approved'] = true;
  }
}

http_response_code(201);

header("Content-Type: application/json");
echo json_encode($result);
