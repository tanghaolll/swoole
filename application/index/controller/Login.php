<?php
namespace app\index\controller;


use app\common\lib\Redis;
use app\common\lib\redis\Predis;
use app\common\lib\Util;

class Login
{
   public function index(){

      $phoneNum = intval($_GET['phone_num']);
      $code = intval($_GET['code']);
      if(empty($phoneNum) || empty($code)){
          return Util::show(config("error"),"phone or code is error");
      }
      $redisCode = Predis::getInstance()->get(Redis::smsKey($phoneNum));
      if($redisCode == $code){
          $data = [
              'user' => $phoneNum,
              'srcKey' => md5(Redis::userKey($phoneNum)),
              'time' => time(),
              'isLogin' => true
          ];

          Predis::getInstance()->set(Redis::userKey($phoneNum),$data);
          return Util::show(config("success"),'ok',$data);
      }else{
          return Util::show(config("error"),'login error');

      }


   }
}
