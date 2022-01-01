<?php
//desc 测试网络延迟
//usage <域名或IP>
function permission(){
	global $allow_user;
	global $allow_group;
	global $deny_user;
	global $deny_group;
};

function msg_handler($args){
	if (do_cooldown('ping',30,$args)) {return;};
    exec("ping -c 2 ".$args['command'], $retval);
	send_group_msg($args["group_id"],implode(PHP_EOL,$retval));
};
?> 
