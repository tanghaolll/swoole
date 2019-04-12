<?php
namespace app\common\lib\redis;
class Predis {
    public $redis = "";
    /**
     * 定义单例模式的私有静态变量
     * @var null
     */
    private static $_instance = null;
    public  static  function getInstance(){
        if(empty(self::$_instance)){
            self::$_instance  = new self();
        }
        return self::$_instance;
    }
    private function __construct()
    {
        $this->redis = new \Redis();
       $result = $this->redis->connect(config("redis.host"), config("redis.port"),config("redis.connectOut"));
        if ($result === false){
            throw  new \Exception('redis connect erroe ');

        }
    }

    /**
     * set
     * @param $key
     * @param $val
     * @param int $time
     * @return bool|string
     */
    public function set($key,$val,$time = 0){
        if(!$key){
            return '';
        }
        if(is_array($val)){
            $val = json_encode($val);
        }
        if(!$time){
            return $this->redis->set($key,$val);
        }
        return $this->redis->setex($key,$time,$val);
    }

    /**
     * get
     * @param $key
     * @return bool|string
     */
    public function get($key){
        if(!$key){
            return '';
        }
        return $this->redis->get($key);
    }

}