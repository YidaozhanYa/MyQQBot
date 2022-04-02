<?php
//desc 一言（迫真） (复刻自 Ayatale)
//usage <无参数>

function permission(){
	global $allow_user;
	global $allow_group;
	global $deny_user;
	global $deny_group;
};

function msg_handler($args){
	send_msg($args,strval("[CQ:image,file=file://".getcwd()."/images/dingzhen/".rand(1,161).".jpg]"));
};

?>