<?php
//desc 查询指定文件在哪个 ArchLinux 软件包中
//usage <文件名（非路径）>

function permission(){
	global $allow_user;
	global $allow_group;
	global $deny_user;
	global $deny_group;
};

function msg_handler($args){
	#$pkgfile=json_decode(get_data("http://82.156.27.226:5557/".$args["command"],0,0),true)['data'];
	exec("pkgfile -s ".$args["command"]." -v -w",$pkgfile);
	$output="📄 文件 ".$args["command"]." 的查询结果: ".PHP_EOL;
	foreach($pkgfile as $line){
		$output=$output."- ".str_replace("	",PHP_EOL."  ",$line).PHP_EOL;
	};
	send_msg_topicture($args,trim($output),"kde");
	return;
};
?>
