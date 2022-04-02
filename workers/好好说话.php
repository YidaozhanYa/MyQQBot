<?php
//desc 「能不能好好说话」拼音缩写查询
//usage <带拼音缩写的句子或拼音缩写>

function permission(){
	global $allow_user;
	global $allow_group;
	global $deny_user;
	global $deny_group;
};

function msg_handler($args){
	$output="";
	$translate_json=json_decode(post_data("https://lab.magiconch.com/api/nbnhhsh/guess",0,0,"text=\"".$args["command"]."\""),true);
	foreach($translate_json as $line){
		$output=$output.$line['name']."：";
		foreach($line['trans'] as $trans){
			$output=$output.$trans."  ";
		};
		$output=$output.PHP_EOL;
	};
	if (strlen($output)>1000) {
		send_msg_topicture($args,trim($output),"kde");
	} else {
		send_msg($args,trim($output));
	};
	return;
};
?>