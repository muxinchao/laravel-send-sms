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
     * 
     */
    private  static function getCurlInit($data, $url)
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

        if ($result[1] == 0) {
            return "curl success";
        } else {
            return "curl error" . $result[1];
        }
    }
}

