<?php
require 'vendor/autoload.php';

/*
  Configure
*/
$redis = new Predis\Client('');

//slim conf
$config = ['settings' => [
  'addContentLengthHeader' => false,
  'displayErrorDetails' => true
]];

$app = new Slim\App($config);

require __DIR__ . '/app/routes.php';
require __DIR__ . '/app/dependencies.php';

$app->run();
