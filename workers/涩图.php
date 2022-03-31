<?php
//desc 获取一张来自 Pixiv 的随机涩图
//usage 无参数
function permission(){
	global $allow_user;
	global $allow_group;
	global $deny_user;
	global $deny_group;
};

function msg_handler($args){
	$setu=json_decode(get_data("https://api.lolicon.app/setu/v2",0,0),true)['data'][0];
	$output=$setu['title']." By ".$setu['author'].PHP_EOL;
	$output=$output."Pixiv ID: ".$setu['pid'].PHP_EOL;
	$output=$output."标签 (base64): ".base64_encode(json_encode($setu['tags'],JSON_UNESCAPED_UNICODE)).PHP_EOL;
	send_msg($args,$output);
};
?> 