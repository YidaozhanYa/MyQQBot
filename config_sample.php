<?php
function global_config(){
	// 机器人相关
	define('CQHTTP_PORT',5700); // go-cqhttp 运行的端口
	define('CMD_PREFIX','::'); // 命令前缀，需要同时在 go-cqhttp 事件过滤器中设置
	define('CMD_PREFIX_BACKUP','：：'); // 另外一个命令前缀，可选
	define('NOPERMIT_USER_ERROR',true); // 当某人没权限执行命令时是否发送错误消息
	define('NOPERMIT_GROUP_ERROR',false); // 当某群没权限执行命令时是否发送错误消息
	define('CMDNOTFOUND_ERROR',false); // 当命令未找到时是否发送错误消息

	// 帐号相关
	define('ADMIN',array(3526514925)); // 管理员 QQ 号
	define('SUPERADMIN',3526514925); // 超级管理员 QQ 号
	//define('TGRCODE',"170.187.159.184"); // 马造备用 API
	define('TGRCODE',"tgrcode.com"); // 马造 API
	define('SMMWE_ACCOUNT',"xxx"); // SMMWE 帐号
	define('SMMWE_PASSWD',"123"); // SMMWE 密码
	define('SMMWE_DISCORDID',"123"); // SMMWE DiscordID
	define('GITHUB_TOKEN','ghp_xxx'); // GitHub Token
	define('BOT_UIN',3526514925); // 机器人 QQ 号

	// 频道相关
	define('NOPERMIT_CHANNEL_ERROR',false); // 当某子频道没权限执行命令时是否发送错误消息
	define('ENABLE_GUILD_CMDS',array('帮助','说')); // 允许在频道工作的命令
	define('GUILD_ID','123'); // 机器人工作的频道 ID
	define('ENABLE_CHANNEL_ID',true); // 是否启用只在单个子频道工作
	define('CHANNEL_ID','123'); // 机器人工作的子频道 ID

	// 帮助命令表
    define('CMDLIST',array('基本'=>array('基本命令',array('帮助','说','统计')),'游戏'=>array('游戏相关命令',array('马造查图','马造查玩家','smmwe','原神祈愿')),'工具'=>array('网络或工具相关命令',array('ping','arch','github','base64','一言','说人话')),'管理'=>array('管理员/超级管理员命令',array('banuser','sh')))); //帮助内的命令分类
};

function global_permission(){
	//在此设定全局权限
	//$allow_user=array(123);
	//allow_group=array(123);
	//$deny_user=array(123);
	//$deny_group=array(123);
};
?>