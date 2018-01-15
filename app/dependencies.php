<?php
// DIC configuration
$container = $app->getContainer();

//Override the default Not Found Handler
$container['notFoundHandler'] = function ($c) {
  return function ($request, $response) use ($c) {
    return $c['response']
      ->withStatus(404)
      ->withHeader('Content-Type', 'text/html')
      ->write('404 Dude!');
  };
};

$container['notAllowedHandler'] = function ($c) {
  return function ($request, $response) use ($c) {
    return $c['response']
      ->withStatus(404)
      ->withHeader('Content-Type', 'text/html')
      ->write('Dude! this page is Forbidden');
  };
};

// predis
$container['redis'] = function ($c) {
  $r = new Predis\Client('redis://kopet:rizopoda@162.208.50.83:6006');
  return $r;
};


/*
  Controller
*/
$container[App\Controller\AddController::class] = function ($c) {
  return new App\Controller\AddController($c->redis);
};
