<?php
function permission(){
	global $allow_user;
	global $allow_group;
	global $deny_user;
	global $deny_group;
};

function msg_handler($args){
	error_log($args["message"]);
    $id=str_replace(CMD_PREFIX."paste ","",$args["message"]);
	$url="https://api.yidaozhan.gq/api/paste-api.php?id=".$id;
	error_log($url);
    $paste=json_decode(get_data($url),true);
	$text=$paste['title']." 由".$paste['username']."发表于".$paste['date'].PHP_EOL.$paste['content'];
	send_group_msg($args["group_id"],$text);
	return;
};


function get_data($url){
	$curl=curl_init();
	//curl_setopt($curl,CURLOPT_HEADER,0);
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
	//curl_setopt($curl,CURLOPT_FOLLOWLOCATION,1);
	//curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, false);
	//curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, false);
	//curl_setopt($curl,CURLOPT_CONNECTTIMEOUT,60);
	curl_setopt($curl,CURLOPT_URL,$url);
	$return_data=curl_exec($curl);
	curl_close($curl);
	return $return_data;
};
?> 
