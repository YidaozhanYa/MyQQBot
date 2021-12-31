<?php
//admin
//desc 运行命令
//usage <命令>

function permission(){
	global $allow_user;
	global $allow_group;
	global $deny_user;
	global $deny_group;
	$allow_user=array(3526514925);
};

function msg_handler($args){
	error_log($args["message"]);
	exec("bash nocolor.sh ".str_replace(CMD_PREFIX."sh ","",$args["message"]),$return);
	send_group_msg($args["group_id"],implode(PHP_EOL,$return));
};
?>