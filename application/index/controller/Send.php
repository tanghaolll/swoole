<?php
namespace app\index\controller;

use app\common\lib\ali\Sms;
use app\common\lib\Redis;
use app\common\lib\Util;

class Send
{
    /**
     * 发送验证码
     */
   public function index(){

            $phoneNum = request()->get('phone_num',0,'intval');
            $phoneNum = intval($_GET['phone_num']);

            if(empty($phoneNum)){
                return Util::show(config('error'),'error');
            }
            //生成随机数
            $code = mt_rand(1000,9999);
            $taskData = [
                'phone' => $phoneNum,
                'code' => $code,
            ];
            //task异步任务
             $_POST['http_server']->task($taskData);
             return Util::show(config("success"),"ok");

          /* try{
               $response =  Sms::sendSms($phoneNum,$code);
            }catch (\Exception $e){
                return Util::show(config('error'),'阿里大于内部异常');
            }*/
            /*if($response->Code === "OK"){
                //redis
                $redis = new \Swoole\Coroutine\Redis();
                $redis->connect(config("redis.host"), config("redis.port"));
                $redis->set(Redis::smsKey($phoneNum),$code,config("redis.time"));
                return Util::show(config('success'));
            }else{
                return Util::show(config('error'),'验证码发送失败');
            }*/


   }
}
