<?php
namespace Laravel\Send\Sms\Agents;

class ChuangLan
{
    /**
     * 手机号
     */
    protected $phone;

    /**
     * 短信内容
     */
    protected $message;

    /**
     * 创蓝253短信平台账号
     */
    protected $account;

    /**
     * 创蓝253短信平台密码
     */
    protected $password;

    /**
     *  创蓝253短信平台url
     */
    protected $url;

    /**
     * 读取配置文件
     */
    public function __construct()
    {
        if (file_exists(config_path('sms.php'))) {
            $curCongfigs = include config_path('sms.php');
        } else {
            $curCongfigs = include dirname(__DIR__) . '/config/default.php';
        }
        $this->account   = $curCongfigs['ChuangLan']['account'];
        $this->password  = $curCongfigs['ChuangLan']['password'];
        $this->url       = $curCongfigs['ChuangLan']['url'];
    }

    /**
     * 发送短信
     * @param $phone
     * @param $message
     */
    public function sendSms($phone, $message)
    {
        $postData    = [];
        //获取信息数组
        $postData    = array(
                'un'    => $this->account,
                'pw'    => $this->password,
                'msg'   => $message,
                'phone' => $phone,
                'rd'    => 1,
            );
        $data   = http_build_query($postData);

        $url    = $this->url;

        $result = self::getCurlInit($data, $url);

        return $result;
    }

    /**
     * 封装253短信平台，短信发送代码
     */
    private static function getCurlInit($data, $url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);

        $result = preg_split("/[,\r\n]/", $output);

        return self::results($result[1]);
    }

    /**
     * 创蓝接口返回参数
     *
     * @return array
     */
    protected static function results($param)
    {
        $array = array(
                    '0'   => '发送成功',
                    '101' => '无此用户',
                    '102' => '密码错',
                    '103' => '提交过快',
                    '104' => '系统忙',
                    '105' => '敏感短信',
                    '106' => '消息长度错',
                    '107' => '错误的手机号码',
                    '108' => '手机号码个数错',
                    '109' => '无发送额度',
                    '110' => '不在发送时间内',
                    '111' => '超出该账户当月发送额度限制',
                    '112' => '无此产品',
                    '113' => 'extno格式错',
                    '115' => '自动审核驳回',
                    '116' => '签名不合法，未带签名',
                    '117' => 'IP地址认证错',
                    '118' => '用户没有相应的发送权限',
                    '119' => '用户已过期',
                    '120' => '内容不是白名单',
                    '121' => '必填参数。是否需要状态报告，取值true或false',
                    '122' => '5分钟内相同账号提交相同消息内容过多',
                    '123' => '发送类型错误(账号发送短信接口权限)',
                    '124' => '白模板匹配错误',
                    '125' => '驳回模板匹配错误',
                 );
        return $array[$param];
    }
}
