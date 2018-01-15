<?php
/*
  v2
*/
$app->group('/v2', function () {
  $this->get('/next', 'App\Controller\AddController:next');
  $this->post('/add', 'App\Controller\AddController:add');
  $this->get('/delete', 'App\Controller\AddController:delete');
  $this->get('/stats', 'App\Controller\AddController:stats');
});


$app->get('/', 'App\Controller\BlogController:home');
