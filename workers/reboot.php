<?php
function permission(){
	global $allow_user;
	global $allow_group;
	global $deny_user;
	global $deny_group;
	$allow_user=array(3526514925);
};

function msg_handler($args){
	error_log("go-cqhttp 正在重启 ...");
	send_group_msg($args["group_id"],"正在重启 ...");
	cqhttp_api("set_restart",array("delay"=>0));
};
?>