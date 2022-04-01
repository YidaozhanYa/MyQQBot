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
	$random=rand(0, 10);
	if (do_cooldown('setu',120,$args)) {return;};
	if ($random>7){
		send_msg($args,'[CQ:image,file=file://'.getcwd().'/images/setu.jpg]');
	} else {
		$setu=json_decode(get_data("https://api.lolicon.app/setu/v2",0,0),true)['data'][0];
		$output=$setu['title']." By ".$setu['author'].PHP_EOL;
		$output=$output."Pixiv ID: ".$setu['pid'].PHP_EOL;
		if ($setu['r18']) {$output=$output." (R18)";};
		send_msg($args,$output);
	};
};
?> 