<?php 
namespace Laravel\Send\Sms\Agents;

class RongLian
{
    /**
     * 开发者主账号ACCOUNT SID
     */
	private $accountSid;

    /**
     * 开发者主账号AUTH TOKEN
     */
	private $accountToken;

    /**
     * 创建应用的APPID
     */
	private $appId;

    /**
     * 生产环境请求地址：app.cloopen.com
     */
	private $serverIP;

    /**
     * 请求端口
     */
	private $serverPort;

    /**
     * REST API版本号保持不变
     */
	private $softVersion;

    /**
     * 时间戳
     */
	private $batch;

    /**
     * 包体格式，可填值：json 、xml
     */
	private $bodyType = "json";

	function __construct()
	{

        if (file_exists(config_path('sms.php'))) {
            $curCongfigs = include config_path('sms.php');
        } else {
            $curCongfigs = include dirname(__DIR__) . '/config/default.php';
        }
        
        $this->accountSid   = $curCongfigs['RongLian']['account_sid'];
        $this->accountToken = $curCongfigs['RongLian']['account_token'];
        $this->appId        = $curCongfigs['RongLian']['app_id'];
        $this->serverIP     = $curCongfigs['RongLian']['server_ip'];
        $this->serverPort   = $curCongfigs['RongLian']['server_port'];
        $this->softVersion  = $curCongfigs['RongLian']['soft_version'];
		$this->batch        = date("YmdHis");
	}

    
     /**
     * 发起HTTPS请求
     */
    function curl_post($url, $data, $header, $post=1)
    {
        //初始化curl
        $ch = curl_init();
        //参数设置  
        $res= curl_setopt ($ch, CURLOPT_URL, $url);  
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, $post);
        if ($post)
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $result = curl_exec($ch);
        //连接失败
        if ($result == FALSE) {
            if ($this->bodyType == 'json') {
                $result = "{\"statusCode\":\"172001\",\"statusMsg\":\"网络错误\"}";
            } else {
                $result = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><Response><statusCode>172001</statusCode><statusMsg>网络错误</statusMsg></Response>"; 
            }    
        }

        curl_close($ch);
        return $result;
    } 

    
   /**
    * 发送模板短信
    * @param to 短信接收彿手机号码集合,用英文逗号分开
    * @param datas 内容数据
    * @param $tempId 模板Id
    */       
    function sendSMS($to, $datas, $tempId)
    {
        //主帐号鉴权信息验证，对必选参数进行判空。
        $auth = $this->accAuth();
        if ($auth != "") {
            return $auth;
        }
        // 拼接请求包体
        if ($this->bodyType == "json") {
            $data = "";
            for ($i=0; $i<count($datas); $i++) {
              $data = $data . "'" . $datas[$i] . "',"; 
            }
            $body = "{'to':'$to','templateId':'$tempId','appId':'$this->appId','datas':[".$data."]}";
        } else {
            $data = "";
            for ($i=0; $i<count($datas); $i++) {
                $data = $data . "<data>" . $datas[$i] . "</data>"; 
            }
            $body = "<TemplateSMS>
                     <to>$to</to> 
                     <appId>$this->appId</appId>
                     <templateId>$tempId</templateId>
                     <datas>" . $data . "</datas>
                     </TemplateSMS>";
        }
        // 大写的sig参数 
        $sig =  strtoupper(md5($this->accountSid . $this->accountToken . $this->batch));
        // 生成请求URL        
        $url = "https://$this->serverIP:$this->serverPort/$this->softVersion/Accounts/$this->accountSid/SMS/TemplateSMS?sig=$sig";
        // 生成授权：主帐户Id + 英文冒号 + 时间戳。
        $authen = base64_encode($this->accountSid . ":" . $this->batch);
        // 生成包头  
        $header = array("Accept:application/$this->bodyType","Content-Type:application/$this->bodyType;charset=utf-8","Authorization:$authen");
        // 发送请求
        $result = $this->curl_post($url, $body, $header);
        if ($this->bodyType == "json") {//JSON格式
           $datas = json_decode($result); 
        } else { //xml格式
           $datas = simplexml_load_string(trim($result," \t\n\r"));
        }
        //重新装填数据
        if ($datas->statusCode == 0) {
            if ($this->bodyType == "json") {
                $datas->TemplateSMS = $datas->templateSMS;
                unset($datas->templateSMS);   
            }
        }
 
        return $datas; 
    } 

    /**
    * 主帐号鉴权
    */   
    function accAuth()
    {
       if ($this->serverIP == "") {
            $data             = new stdClass();
            $data->statusCode = '172004';
            $data->statusMsg  = 'IP为空';
            return $data;
        }
        if ($this->serverPort <= 0) {
            $data             = new stdClass();
            $data->statusCode = '172005';
            $data->statusMsg  = '端口错误（小于等于0）';
            return $data;
        }
        if ($this->softVersion == "") {
            $data             = new stdClass();
            $data->statusCode = '172013';
            $data->statusMsg  = '版本号为空';
            return $data;
        } 
        if ($this->accountSid=="") {
            $data             = new stdClass();
            $data->statusCode = '172006';
            $data->statusMsg  = '主帐号为空';
            return $data;
        }
        if ($this->accountToken == "") {
            $data             = new stdClass();
            $data->statusCode = '172007';
            $data->statusMsg  = '主帐号令牌为空';
            return $data;
        }
        if ($this->appId == "") {
            $data             = new stdClass();
            $data->statusCode = '172012';
            $data->statusMsg  = '应用ID为空';
            return $data;
        }   
    }
}

