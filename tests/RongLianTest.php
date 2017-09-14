<?php
namespace Laravel\Send\Sms\Agents;

class RongLianTest
{
    /**
     *  容联短信平台的功能使用
     */
    public function testForRongLian()
    {
        //测试引入ChuangLan.php
        $object = include dirname(__DIR__) . '/src/Agents/RongLian.php';
        var_dump($object);
        //得到实例
        $obj    = new RongLian();
        var_dump($obj);
        //发送方法的使用
        $result = $obj->sendSms('18910072065', ['我又做了一个测试', '1'], 1);
        var_dump($result);

        return $result;
    }
}

$res = new ChuangLanTest();

$res->testForChuangLan();
