<?php
function permission(){
	global $allow_user;
	global $allow_group;
	global $deny_user;
	global $deny_group;
};

function msg_handler($args){

	error_log($args["message"]);
	send_group_msg($args["group_id"],str_replace(CMD_PREFIX."echo ","",$args["message"]));
};
?>