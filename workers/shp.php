<?php
//desc 运行命令
//usage <命令>

function permission(){
	global $allow_user;
	global $allow_group;
	global $deny_user;
	global $deny_group;
	$allow_user=array(SUPERADMIN);
};

function msg_handler($args){
	exec($args['command'],$return);
	send_msg_topicture($args,implode(PHP_EOL,$return),"kde");
};
?>