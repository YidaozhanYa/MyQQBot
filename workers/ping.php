<?php
function permission(){
	global $allow_user;
	global $allow_group;
	global $deny_user;
	global $deny_group;
};

function msg_handler($args){
	error_log($args["message"]);
    exec("ping -c 2 ".str_replace(CMD_PREFIX."ping ","",$args["message"]), $retval);
	send_group_msg($args["group_id"],implode(PHP_EOL,$retval));
};
?> 
