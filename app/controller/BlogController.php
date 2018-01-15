<?php
namespace App\Controller;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

final class BlogController {

  public function home(Request $request, Response $response){

    $r = shell_exec('free -m');
    $r = str_replace(array("\n","\r\n","\r","\t",'    ','   ','  '),' ',$r);

    $r = explode('cached',$r);
    $r = implode($r);
    $r = str_replace(array("\n","\r\n","\r","\t",'    ','   ','  '),' ',$r);
    $r = explode('Mem: ',$r);
    $r = $r[1];
    $r = explode(' ',$r);

    $total  = number_format($r[0]).'Mb';
    $usage  = number_format($r[1]).'Mb';
    $free   = number_format($r[2]).'Mb';

    $uptime = shell_exec("uptime");
    $uptime = str_replace(array("\n","\r\n","\r","\t",'    ','   ','  '),' ',$uptime);
    $uptime = explode(',',$uptime);
    $uptime = $uptime[0];

    $percent  = $r[1]/$r[0];

    if($_SERVER['HTTP_HOST']=='localhost'){
      $persen   = mt_rand(1,100);
    }else{
      $persen   = number_format( $percent * 100, 2 );
    }

    $res = [
      'desc'              => 'IKAMAI 2018 - AlFatihah Insert Kiwot',
      'status'            => 'good',
      'versi'             => 'v2',
      'name'              => str_replace('.herokuapp.com','',$_SERVER['HTTP_HOST']),
      'node'              => gethostname(),
      'server_name'       => $_SERVER['HTTP_HOST'],
      'server_software'   => $_SERVER['SERVER_SOFTWARE'],
      'remote_addr'       => $_SERVER['REMOTE_ADDR'],
      'memtotal'          => $total,
      'memusage'          => $usage,
      'memfree'           => $free,
      'mempercent'        => $persen,
      'uptime'            => trim(str_replace('up  ','',$uptime))
    ];

    $response = $response
      ->withAddedHeader('Access-Control-Allow-Methods','POST, GET, OPTIONS')
      ->withAddedHeader('Access-Control-Allow-Origin','*');

    $r = $response->withJson($res);
    return $r;
  }

}
