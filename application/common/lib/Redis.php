<?php
namespace app\common\lib;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/10 0010
 * Time: 14:20
 */
class Redis{
    /**
     * 验证码前缀
     * @var string
     */
    public static $pre = "sms_";
    public static $userpre = "user_";

    /**
     * 存储验证码 redis key
     * @param $phone
     * @return string
     */
    public static function smsKey($phone){
        return self::$pre.$phone;
    }

    /**
     * 用户的key
     * @param $phone
     * @return string
     */
    public static function userKey($phone){
        return self::$userpre.$phone;
    }
}