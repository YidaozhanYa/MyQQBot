<?php
//desc 发送一条消息
//usage <消息内容>

function permission(){
	global $allow_user;
	global $allow_group;
	global $deny_user;
	global $deny_group;
};

function msg_handler($args){
	send_msg($args,$args["command"]);
};
?>