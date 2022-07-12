<?php
require __DIR__ .  '/vendor/autoload.php';
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

$logger = new Logger('my_logger');
$logger->pushHandler(new StreamHandler(__DIR__ . '/app.log', Logger::DEBUG));

$json = file_get_contents('php://input');
$logger->info('execute webhook');
$logger->info('body');
$logger->info($json);
$logger->info('$_GET');
$logger->info(json_encode($_GET));

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
