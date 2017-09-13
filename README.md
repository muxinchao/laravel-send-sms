# laravel send sms

Based on laravel5 development of lightweight mobile phone SMS package, features: simple and flexible

	> support：ChuangLan253 SMS，RongLian SMS

	> Suitable for the scene：Phone verification


## installation

Via Composer

``` php
$ composer require laravel-send-sms/laravel-send-sms=dev-master
```

composer.json

``` php
"laravel-send-sms/laravel-send-sms":"dev-master"
```

## Configuration

``` php
//service provider
'providers' => [
		//...
		Laravel\Send\Sms\SmsServiceProvider::class,
	]

//Alias
'aliases' => [
	//...
	'Sms' => Laravel\Send\Sms\Agents\SmsManager::class,
]

//Create a configuration file
$ php artisan vendor::pulish 
```

## Use examples

``` php
//...
use Sms;

//ChuangLan
$obj = new Sms();
$obj->manager('ChuangLan')->sendSms('手机', '短信内容');

//RongLan
$obj = new Sms();
$obj->manager('RongLan')->sendSms('手机',['8765', '1'], 1);参数详情参照容联文档
```

## Configuration file description

``` php
//Generate the sms.php file
return [

	//ChuangLan253 SMS plateform
	'ChuangLan' => [
	    'account'  => '095389',//填写您自己的账号 (your account)
		'password' => '7LOKagPs557e',//填写您自己的密码 (your password)
		'url'      => 'http://sms.253.com/msg/send',
	],
	//RongLian SMS platform
	'RongLian' => [
		'account_sid'   => '8a216da85e6fff2b015e74aba45d0235',//You can see the developer's primary account ACCOUNT SID on the front page of the console
		'account_token' => 'c7ee385c4a804715a45b815271059e94',//You can see the developer 's main account on the front page of the console
		'app_id'        => '8a216da85e6fff2b015e74aba5ba023c',//Use the APPID of the app already created in the Admin console
		'server_ip'     => 'sandboxapp.cloopen.com',          //Test environment (production environment request address: app.cloopen.com)
		'server_port'   => '8883',                            //Request port
		'soft_version'  => '2013-12-26',                      //REST API version number remains unchanged
	]
];


## Security

> If you discover any security related issues, please email 1046072048@qq.com instead of using the issue tracker.


## License

The MIT License (MIT). 

