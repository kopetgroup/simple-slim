<?php
namespace App\Controller;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use \Predis as Predis;

//extends BaseController
final class AddController {

  private $redis;

  public function __construct($redis) {
    $this->redis = $redis;
  }

  public function add(Request $request, Response $response){

    $data = json_decode(file_get_contents('php://input'), true);
    if($data){

      $username = $data['username'];
      $domain = $data['domain'];
      $category = $data['category'];
      $kiwots = explode("\n",$data['kiwot']);

      if(!$category){
        $res = [
          'status' => 'failed',
          'msg' => 'need category'
        ];
      }elseif(!$kiwots){
        $res = [
          'status' => 'failed',
          'msg' => 'need kiwot'
        ];
      }elseif(!$domain){
        $res = [
          'status' => 'failed',
          'msg' => 'need domain'
        ];
      }else{

        $e = [];
        foreach($kiwots as $k){
          $t = $this->redis->lpush('kiwot_'.$domain,$k);
          $e[] = [
            'key' => trim($k),
            'val' => $t
          ];
        }

        $res = [
          'username' => $username,
          'domain' => $domain,
          'data' => $e
        ];

      }


    }else{
      $res = [
        'status' => 'failed',
        'msg' => 'need data'
      ];
    }

    $response = $response
      ->withAddedHeader('Access-Control-Allow-Methods','POST, GET, OPTIONS')
      ->withAddedHeader('Access-Control-Allow-Origin','*');

    $r = $response->withJson($res);
    return $r;

  }

  public function next(Request $request, Response $response){

    if(isset($_GET['domain'])){

      $key = 'kiwot_'.$_GET['domain'];
      $tot = $this->redis->llen($key);
      $id  = mt_rand(0,($tot-1));
      $kwt = $this->redis->lindex($key, $id);
      if($tot==0){
        $res = [
          'status' => 'entek',
        ];
      }else{
        $res = [
          'status' => 'success',
          'id' => $id,
          'key' => $key,
          'total' => $tot,
          'data' => $kwt
        ];
      }

    }else{
      $res = [
        'status' => 'failed',
        'msg' => 'need domain'
      ];
    }

    $response = $response
      ->withAddedHeader('Access-Control-Allow-Methods','POST, GET, OPTIONS')
      ->withAddedHeader('Access-Control-Allow-Origin','*');
    $r = $response->withJson($res);
    return $r;

  }

  public function delete(Request $request, Response $response){

    $pop = $this->redis->lrem('kiwot_'.$_GET['domain'], $_GET['id'], $_GET['kiwot']);
    $response = $response
      ->withAddedHeader('Access-Control-Allow-Methods','POST, GET, OPTIONS')
      ->withAddedHeader('Access-Control-Allow-Origin','*');
    $r = $response->withJson($pop);
    return $r;

  }

  public function stats(Request $request, Response $response){

    if(isset($_GET['domain'])){
      $d = $_GET['domain'];
      $res = [
        'totalkey' => $this->redis->llen('kiwot_'.$d),
        'totalimg' => $this->redis->llen('gambar_'.$d),
        'domain' => $d
      ];
    }else{
      $res = [
        'msg' => 'need domain',
        'status' => 'failed'
      ];
    }
    $response = $response
      ->withAddedHeader('Access-Control-Allow-Methods','POST, GET, OPTIONS')
      ->withAddedHeader('Access-Control-Allow-Origin','*');
    $r = $response->withJson($res);
    return $r;
  }

}
