<?php
//desc 签约2012模拟器（
//usage <消息内容>

function permission(){
	global $allow_user;
	global $allow_group;
	global $deny_user;
	global $deny_group;
};

function msg_handler($args){
	$qy=json_decode(get_data("https://www.jsonin.com/fenci.php?type=cixing&msg=".rawurlencode($args["command"]),0,0),true);
	$output="";
	foreach($qy as $word){
		$output=$output.$word["word"]." ";
	}
	send_msg($args,trim($output));
}
?>
