<?php

class Http {
    CONST HOST = "0.0.0.0";
    CONST PORT = 8811;
    public $http = null;
    public function __construct (){
        $this->http =  new swoole_http_server(self::HOST,self::PORT);
        $this->http->set(
            [
                'enable_static_handler' => true,
                //静态资源路径
                'document_root' => "/home/wwwroot/demo.victor-t.cn/swoole/public/static",
                'worker_num' => 4,
                'task_worker_num' =>4,

            ]
        );
        $this->http->on("workerstart",[$this,'onWorkerStart']);
        $this->http->on("request",[$this,'onRequest']);
        $this->http->on("task",[$this,'onTask']);
        $this->http->on("finish",[$this,'onFinish']);
        $this->http->on("close",[$this,'onClose']);
        $this->http->start();
    }
    public function onWorkerStart($server,$worker_id){
        define('APP_PATH', __DIR__ . '/../application/');
        define('CONF_PATH', __DIR__.'/../config/');
/*        require __DIR__ . '/../thinkphp/base.php';*/
        require __DIR__ . '/../thinkphp/start.php';
    }
    public function onRequest($request,$response){
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
        $_POST['http_server'] = $this->http;
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
    }




    public function onTask($serv,$taskId,$workerId,$data){
        $obj = new app\common\lib\ali\Sms();
        try{
            $response =  $obj::sendSms($data['phone'],$data['code']);
        }catch (\Exception $e){
            echo $e->getMessage();
        }
        print_r($response);
    }

    /**
     * @param $serv
     * @param $taskId
     * @param $data
     */
    public function onFinish($serv,$taskId,$data){

        echo "taskID:{$taskId}\n";
        echo "finish-data-success：{$data}\n";
    }
    /**
     * @param $http
     * @param $fd
     * 监听关闭事件
     */
    public function onClose($http,$fd){
        echo "clientid:{$fd}\n";
    }

}
new Http();