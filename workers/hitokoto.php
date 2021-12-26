<?php
function permission(){
	global $allow_user;
	global $allow_group;
	global $deny_user;
	global $deny_group;
};

function msg_handler($args){
	$yiyan=json_decode(get_data('https://v1.hitokoto.cn/',0,0),true);
	error_log($args["message"]);
	send_group_msg($args["group_id"],$yiyan['hitokoto'].' —— '.$yiyan['from']);
};
?>