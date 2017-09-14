<?php
namespace Laravel\Send\Sms\Agents;

class ChuangLanTest
{
    /**
     * 创蓝253短信平台的功能使用
     */
    public function testForChuangLan()
    {
        //测试引入ChuangLan.php
        $object = include dirname(__DIR__) . '/src/Agents/ChuangLan.php';
        var_dump($object);
        //得到实例
        $obj    = new ChuangLan();
        var_dump($obj);
        //发送方法的使用
        $result = $obj->sendSms('18910072065', '我又做了一个测试');
        var_dump($result);

        return $result;
    }
}

$res = new ChuangLanTest();

$res->testForChuangLan();
