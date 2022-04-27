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
	exec($args['command'],$return,$retcode);
	send_msg($args,"🖥 命令执行结果：".$retcode.PHP_EOL.implode(PHP_EOL,$return));
};
?>
