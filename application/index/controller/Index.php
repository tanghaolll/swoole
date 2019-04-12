<?php
namespace app\index\controller;

use app\common\lib\ali\Sms;

class Index
{
    public function index()
    {

       return '';
    }
    public function victor(){
        echo date("Ymd H:i:s");
    }
    public function sms(){
        try{
            Sms::sendSms(15642947778,12345);
        }catch (\Exception $e){
            //todo
        }

    }
}
