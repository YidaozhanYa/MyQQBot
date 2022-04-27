<?php
//desc 查看文件内容
//usage <文件路径>

function permission(){
	global $allow_user;
	global $allow_group;
	global $deny_user;
	global $deny_group;
	$allow_user=array(SUPERADMIN);
};

function msg_handler($args){
	$command=getcwd()."/silicon-1 ".$args['command']." -o ".getcwd()."/images/temp.png --background-image ".getcwd()."/images/"."kde.jpg";
	exec($command);
	send_msg($args,'[CQ:image,file=file://'.getcwd().'/images/temp.png]');
	return;
};
?>
