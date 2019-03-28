<?php
namespace app\index\controller;

use app\common\lib\ali\Sms;
use app\common\lib\Util;


class Send
{
    /**
     * 发送验证码
     */
   public function index(){

            $phoneNum = request()->get('phone_num',0,'intval');
            if(empty($phoneNum)){
                return Util::show(config('error'),'error');
            }
            //生成随机数
            $code = mt_rand(1000,9999);
            echo $code;
   }
}
