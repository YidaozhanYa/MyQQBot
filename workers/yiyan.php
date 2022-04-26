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
	$yiyan_url=json_decode(get_data("https://api.aya1.top/randomdj?g=1&r=0",0,0),true)['url'];
	send_msg($args,strval("[CQ:image,file=".$yiyan_url."]"));
};

?>