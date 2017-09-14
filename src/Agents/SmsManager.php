<?php
namespace Laravel\Send\Sms\Agents;

class SmsManager
{
    /**
     * 短信平台名称
     */
    protected $object;

    /**
     * 接收一个平台名称，返回一个平台类实例
     * @param string $object
     */
    public function manager(string $object)
    {
        if (file_exists(__DIR__ . "/$object" . '.php')) {
            include_once(__DIR__ . "/$object" . '.php');
            switch ($object) {
                case 'ChuangLan':
                    return new ChuangLan();
                    break;
                case 'RongLian':
                    return new RongLian();
                    break;
                default:
                    return '参数错误';
                    break;
            }
        } else {
            return '参数错误，不兼容这个短信平台';
        }
        
    }
}
