<?php
$http = new swoole_http_server("0.0.0.0",8811);

$http->set(
    [
        'enable_static_handler' => true,
        //静态资源路径
        'document_root' => "/home/wwwroot/demo.victor-t.cn/swoole/public/static",
        "worker_num" => 5
    ]
);
$http->on("WorkerStart",function (swoole_server $server,$worker_id){
    define('APP_PATH', __DIR__ . '/../application/');
    define('CONF_PATH', __DIR__.'/../config/');
    require __DIR__ . '/../thinkphp/base.php';

});
$http->on("request",function ($request,$response) use($http){
    $_SERVER = [];
    if(isset($request->server)){
        foreach ($request->server as $k => $v){
            $_SERVER[strtoupper($k)] = $v;
        }
    }
    if(isset($request->header)){
        foreach ($request->header as $k => $v){
            $_SERVER[strtoupper($k)] = $v;
        }
    }
    $_GET = [];
    if(isset($request->get)){
        foreach ($request->get as $k => $v){
            $_GET[$k] = $v;
        }
    }
    $_POST = [];
    if(isset($request->post)){
        foreach ($request->post as $k => $v){
            $_POST[$k] = $v;
        }
    }
    ob_start();
    try{
        // 2. 执行应用
        think\App::run()->send();
    }catch (\Exception $e){
        //todo
    }
    $res = ob_get_contents();
    ob_end_clean();
    $response->end($res);
   // $http->close();
});
$http->start();