<?php
//desc 自动翻译为中文或英文，支持多行
//usage <原文本>

function permission(){
	global $allow_user;
	global $allow_group;
	global $deny_user;
	global $deny_group;
};

function msg_handler($args){
	$output="";
	$translate_json=json_decode(get_data("https://fanyi.youdao.com/translate?&doctype=json&type=AUTO&i=".rawurlencode($args["command"]),0,0),true)['translateResult'];
	foreach($translate_json as $line){
		$output=$output.$line[0]['tgt'].PHP_EOL;
	};
	if (strlen($output)>1000) {
		send_msg_topicture($args,trim($output),"kde");
	} else {
		send_msg($args,trim($output));
	};
	return;
};
?>