<?php
namespace Laravel\Send\Sms\Agents;

class SmsTest
{
    /**
     *  平台名称
     */
    protected $agentsName;

    /**
     *  短信平台代码测试
     */
    public function test(string $agentsName)
    {
        include dirname(__DIR__) . '/src/Agents/SmsManager.php';

        $object = new SmsManager();
        // // 创蓝
        // $object->manager($agentsName)->sendSms('18910072065', '加班吧');
        var_dump(__DIR__);
        // 容联
        $object->manager($agentsName)->sendSms('18910072065', ['我就喜欢加班', '1'], 1);
    }
}


$rongLian = new SmsTest();
$rongLian->test('RongLian');
// 把20行代码放开，注释掉23行
// $chuangLan = new SmsTest();
// $chuangLan->test('ChuangLan');
