<?php
function global_config(){
	// 机器人相关
	define('CQHTTP_PORT',5700); // go-cqhttp 运行的端口
	define('CMD_PREFIX','::'); // 命令前缀，需要同时在 go-cqhttp 事件过滤器中设置
	define('CMD_PREFIX_BACKUP','：：'); // 另外一个命令前缀，可选
	define('NOPERMIT_USER_ERROR',true); // 当某人没权限执行命令时是否发送错误消息
	define('NOPERMIT_GROUP_ERROR',false); // 当某群没权限执行命令时是否发送错误消息
	define('CMDNOTFOUND_ERROR',true); // 当命令未找到时是否发送错误消息

	// 帐号相关
	define('ADMIN',array(10001)); // 管理员 QQ 号
	define('SUPERADMIN',10001); // 超级管理员 QQ 号
	define('BOT_UIN',10001); // 机器人 QQ 号
	define('Q2TG_UIN',10001); // Q2TG QQ 号

	// 插件相关
	define('TGRCODE',"tgrcode.com"); // 马造 API
	define('GITHUB_TOKEN','ghp_10001'); // 机器人工作的子频道 ID
	define("GI_API", "10001/api"); // 原神德丽莎抽卡模拟 API
	define("GI_AUTH", "10001");// 原神德丽莎抽卡模拟 Key
	define("ONEMANAGER_ROOT", "10001/Bot/");// 精华消息上传 OneManager 根目录，后面带/
	define("ONEMANAGER_ADMIN", "10001");// OneManager Admin 密码


	// 频道相关
	define('NOPERMIT_CHANNEL_ERROR',false); // 当某子频道没权限执行命令时是否发送错误消息
	define('ENABLE_GUILD_CMDS',array('马造查图','马造查玩家','帮助')); // 允许在频道工作的命令
	define('GUILD_ID','10001'); // 机器人工作的频道 ID
	define('ENABLE_CHANNEL_ID',true); // 是否启用只在单个子频道工作
	define('CHANNEL_ID','10001'); // 机器人工作的子频道 ID

	// 帮助命令表
    define('CMDLIST',array('基本'=>array('基本命令',array('帮助','说','统计')),'游戏'=>array('游戏相关命令',array('马造查图','马造查玩家','原神祈愿','原神祈愿旧版')),'其它'=>array('不正经或无法分类的命令',array('涩图','好好说话','kk','yiyan',"签约",'发病','搬石砸脚')),'工具'=>array('网络及工具相关命令',array('ping','查包','pkgfile','pkgbuild','github','base64','翻译')),'管理'=>array('管理员/超级管理员命令',array('banuser','sh','shp','botctl','cat')))); //帮助内的命令分类
	define('ALIAS',array('arch'=>"查包","help"=>"帮助","echo"=>"说","stats"=>"统计","wish"=>"原神祈愿","setu"=>"涩图","色图"=>"涩图","封禁"=>"banuser","说人话"=>"翻译","hhsh"=>"好好说话","cmd"=>"sh","pkg"=>"查包","PKGBUILD"=>"pkgbuild","qy"=>"签约","fy"=>"翻译","fabing"=>"发病","bszj"=>"搬石砸脚")); //命令别名
};

function global_permission(){
	//在此设定全局权限
	//$allow_user[]=123;
	//allow_group[]=123;
	$deny_user=array(10001);
	//$deny_group[]=123;
	$allow_group=array(10001);
};
