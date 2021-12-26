<?php
function global_config(){
	// 配置常量
	define('CQHTTP_PORT',5700); // go-cqhttp 运行的端口
	define('CMD_PREFIX','!!'); // 命令前缀，需要同时在 go-cqhttp 事件过滤器中设置
	define('NOPERMIT_USER',true); // 当某人没权限执行命令时是否发送错误消息
	define('NOPERMIT_GROUP',false); // 当某群没权限执行命令时是否发送错误消息

	define('TGRCODE',"tgrcode.com"); // 马造 API
	define('SMMWE_ACCOUNT',"YidaozhanYa"); // SMMWE 帐号
	define('SMMWE_PASSWD',"PASSWORD"); // SMMWE 密码
	define('SMMWE_DISCORDID',"111111111111111111"); // SMMWE DiscordID
};

function global_permission(){
	//在此设定全局权限
	//$allow_user[]=123;
	//allow_group[]=123;
	//$deny_user[]=123;
	//$deny_group[]=123;
};
?>