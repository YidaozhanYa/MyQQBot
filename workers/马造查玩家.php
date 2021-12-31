<?php
//desc 查询《超级马里奥制造 2》玩家信息
//usage <玩家ID>，带不带横杠均可

function permission(){
	global $allow_user;
	global $allow_group;
	global $deny_user;
	global $deny_group;
};

function msg_handler($args){
	error_log($args["message"]);
    $mkr_id=str_replace(CMD_PREFIX."马造查玩家 ","",$args["message"]);
    $mkr_id=str_replace('-','',$mkr_id);
    $mkr_id=str_replace(' ','',$mkr_id);
    $mkr_id=strtoupper($mkr_id);
    if (strlen($mkr_id)!==9){
    	send_group_msg($args["group_id"],"无效的玩家 ID。");
    	return;
	};
	if (do_cooldown('mm2',60,$args)) {return;};
	$url="https://".TGRCODE."/mm2/user_info/".$mkr_id;
	error_log($url);
	$message_id=send_group_msg($args["group_id"],"正在查询玩家 ".$mkr_id." ...");
    $mkr_arr=json_decode(get_data($url,0,0),true);
	if (is_null($mkr_arr['error'])==false){
		send_group_msg($args["group_id"],'发生错误：'.$mkr_arr['error']);
		return;
	};
	$output="";
	$output=$output.'工匠名：'.$mkr_arr['name'].PHP_EOL;
	$output=$output.'玩过的关卡数：'.$mkr_arr['courses_played'].PHP_EOL;
	$output=$output.'通过的关卡数：'.$mkr_arr['courses_cleared'].PHP_EOL;
	$output=$output.'通过的世界数：'.$mkr_arr['unique_super_world_clears'].PHP_EOL;
	$output=$output.'死亡次数：'.$mkr_arr['courses_deaths'].PHP_EOL;
	$output=$output.'关卡数：'.$mkr_arr['uploaded_levels'].PHP_EOL;
	$output=$output.'首插数：'.$mkr_arr['first_clears'].PHP_EOL;
	$output=$output.'纪录数：'.$mkr_arr['world_records'].PHP_EOL;
	$output=$output.'工匠点数：'.$mkr_arr['maker_points'].PHP_EOL;
	$output=$output.'得到的赞：'.$mkr_arr['likes'].PHP_EOL;
	$output=$output.'简单团纪录：'.$mkr_arr['easy_highscore'].PHP_EOL;
	$output=$output.'普通团纪录：'.$mkr_arr['normal_highscore'].PHP_EOL;
	$output=$output.'困难团纪录：'.$mkr_arr['expert_highscore'].PHP_EOL;
	$output=$output.'极难团纪录：'.$mkr_arr['super_expert_highscore'];
	send_group_msg($args["group_id"],$output);
	
	$output="";
	$output=$output.'对战积分：'.$mkr_arr['versus_rating'].' 段位：'.$mkr_arr['versus_rank_name'].PHP_EOL;
	$output=$output.'总场数：'.$mkr_arr['versus_plays'].PHP_EOL;
	$output=$output.'胜利场数：'.$mkr_arr['versus_won'].PHP_EOL;
	$output=$output.'失败场数：'.$mkr_arr['versus_lost'].PHP_EOL;
	$output=$output.'掉线场数：'.$mkr_arr['versus_disconnected'].PHP_EOL;
	$output=$output.'击杀数：'.$mkr_arr['versus_kills'].PHP_EOL;
	$output=$output.'被击杀数：'.$mkr_arr['versus_killed_by_others'].PHP_EOL;
	$output=$output.'合作场数：'.$mkr_arr['coop_plays'].PHP_EOL;
	$output=$output.'合作过关场数：'.$mkr_arr['coop_clears'].PHP_EOL;
	$output=$output.'网络区服：'.$mkr_arr['region_name'];
	send_group_msg($args["group_id"],$output);
	
	delete_msg($message_id);
	
	
	return;
};


?> 
