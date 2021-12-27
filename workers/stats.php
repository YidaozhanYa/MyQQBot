<?php
function permission(){
	global $allow_user;
	global $allow_group;
	global $deny_user;
	global $deny_group;
};

function msg_handler($args){
	error_log($args["message"]);
	$return="";
	$return=$return."灵梦 Bot By 是一刀斩哒，Powered by go-cqhttp".PHP_EOL;
	$return=$return."系统：".php_uname().PHP_EOL;
	$return=$return."服务器：".$_SERVER['SERVER_SOFTWARE'].PHP_EOL;
	$return=$return."IP：".$_SERVER["HTTP_HOST"].':'.$_SERVER['SERVER_PORT'].PHP_EOL;
	$stats=cqhttp_api('get_status',array())['stat'].PHP_EOL;
	$return=$return."本次运行情况如下：".PHP_EOL;
	$return=$return."收包数：".$stat['packet_received'].PHP_EOL;
	$return=$return."发包数：".$stat['packet_sent'].PHP_EOL;
	$return=$return."丢包数：".$stat['packet_lost'].PHP_EOL;
	$return=$return."收消息数：".$stat['message_received'].PHP_EOL;
	$return=$return."发消息数：".$stat['message_sent'].PHP_EOL;
	$return=$return."连接中断数：".$stat['disconnect_times'].PHP_EOL;
	$return=$return."掉线数：".$stat['lost_times'];
	send_group_msg($args["group_id"],$return);
};
?>