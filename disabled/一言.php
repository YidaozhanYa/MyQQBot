<?php
//desc 发送一条一言
//usage 无
function permission(){
	global $allow_user;
	global $allow_group;
	global $deny_user;
	global $deny_group;
};

function msg_handler($args){
	$yiyan=json_decode(get_data('https://v1.hitokoto.cn/',0,0),true);
	send_msg($args,$yiyan['hitokoto'].' —— '.$yiyan['from']);
};
?>