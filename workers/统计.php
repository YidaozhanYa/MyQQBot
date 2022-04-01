<?php
//desc 查看统计信息
//usage 无
function permission(){
	global $allow_user;
	global $allow_group;
	global $deny_user;
	global $deny_group;
};

function msg_handler($args){
	$return="";
	$stat=cqhttp_api('get_status',array())['stat'];
	$ver=cqhttp_api('get_version_info',array())['app_version'];
	$ip=str_replace(PHP_EOL,"",get_data('https://api.ip.sb/ip',0,0));
	$return=$return."灵梦 Bot By 是一刀斩哒，Powered by go-cqhttp".PHP_EOL;
	$return=$return."系统：".php_uname().PHP_EOL;
	$return=$return."服务器：".$_SERVER['SERVER_SOFTWARE'].PHP_EOL;
	$return=$return."IP：".$ip.":".$_SERVER["SERVER_PORT"].PHP_EOL;
	$return=$return."go-cqhttp 版本：".$ver.PHP_EOL;
	$return=$return."本次运行情况如下：".PHP_EOL;
	$return=$return."收包数：".$stat['packet_received'].PHP_EOL;
	$return=$return."发包数：".$stat['packet_sent'].PHP_EOL;
	$return=$return."丢包数：".$stat['packet_lost'].PHP_EOL;
	$return=$return."收消息数：".$stat['message_received'].PHP_EOL;
	$return=$return."发消息数：".$stat['message_sent'].PHP_EOL;
	$return=$return."连接中断数：".$stat['disconnect_times'].PHP_EOL;
	$return=$return."掉线数：".$stat['lost_times'];
	exec("bash nocolor.sh neofetch --off --ascii_bold off --color_blocks off",$return2);
	send_msg_topicture($args,$return.PHP_EOL.PHP_EOL.implode(PHP_EOL,$return2),"kde");
	return;
};
?>