<?php
//desc 查询《超级马里奥制造 2》关卡信息
//usage <关卡ID>，带不带横杠均可

define('game_style',array('超马1','超马3','超马世界','新超马U','超马3D世界'));
define('difficulty',array('❇ 简单','✴ 普通','☢ 困难','🈲 极难'));
define('theme',array('Castle'=>'🏰 城堡','Airship'=>'🛸 飞行船','Ghost house'=>'🌃 鬼屋','Underground'=>'🪨 地下','Sky'=>'✈ 天空','Snow'=>'☃ 雪原','Desert'=>'🏜 沙漠','Overworld'=>'🏞 平原','Forest'=>'🌲 丛林','Underwater'=>'🐳 水中'));
define('pretty_tag',array(1=>"🎮 标准",
2 => "🧩 解谜",
3 => "⏰ 计时挑战",
4 => "📨 自动卷轴",
5 => "🎁 自动马力欧",
6 => "☑ 一次通过",
7 => "⚔ 多人对战",
8 => "🖥 机关设计",
9 => "🎹 音乐",
10 => "🖼 美术",
11 => "🕹 技巧",
12 => "🔫 射击",
13 => "🪚 BOSS战",
14 => "👤 单打",
15 => "🏹 林克"));
function permission(){
	global $allow_user;
	global $allow_group;
	global $deny_user;
	global $deny_group;
};

function msg_handler($args){
    $lvl_id=$args['command'];
    $lvl_id=str_replace('-','',$lvl_id);
    $lvl_id=str_replace(' ','',$lvl_id);
    $lvl_id=strtoupper($lvl_id);
        if (strlen($lvl_id)!==9){
    	send_msg($args,"❌ 无效的关卡 ID。");
    	return;
	};
	$url="https://".TGRCODE."/mm2/level_info/".$lvl_id;
	error_log($url);
	$message_id=send_msg($args,"⏰ 正在查询关卡 ".$lvl_id." ...");
    $lvl_arr=json_decode(get_data($url,0,0),true);
	error_log(json_encode($lvl_arr));
	if (is_null($lvl_arr['error'])==false){
		send_msg($args,'❌ 发生错误: '.$lvl_arr['error']);
		return;
	};
	$output="";
	$output=$output."🕹 ".$lvl_arr['name'].PHP_EOL;
	$output=$output.'🌏 场景: '.game_style[$lvl_arr['game_style']].'  '.theme[$lvl_arr['theme_name']].PHP_EOL;
	$output=$output."🏷 标签: ".pretty_tag[$lvl_arr['tags'][0]]." , ".pretty_tag[$lvl_arr['tags'][1]].PHP_EOL;
	$output=$output."👤 作者: ".$lvl_arr['uploader']['name'].' ('.$lvl_arr['uploader']['code'].")".PHP_EOL;
	$output=$output."📤 日期: ".explode(" ",$lvl_arr['uploaded_pretty'])[0].PHP_EOL;
	$output=$output."📄 简介: ".$lvl_arr['description'].PHP_EOL;
	$output=$output.$lvl_arr['attempts'].' 🕹 / '.$lvl_arr['clears']." 🚩  (".$lvl_arr['clear_rate'].' '.difficulty[$lvl_arr['difficulty']].')'.PHP_EOL;
	$output=$output.$lvl_arr['likes'].'❤ , '.$lvl_arr['boos'].'💔'.PHP_EOL;
	$output=$output.'👤 首插者: '.$lvl_arr['first_completer']['name'].PHP_EOL;
	$output=$output.'👤 纪录: '.$lvl_arr['record_holder']['name'].' ⏰ '.$lvl_arr['world_record_pretty'];
	exec('curl https://'.TGRCODE.'/mm2/level_thumbnail/'.$lvl_id.' > "'.getcwd().'/dl/temp.png"');
	exec('curl https://'.TGRCODE.'/mm2/level_entire_thumbnail/'.$lvl_id.' > "'.getcwd().'/dl/temp_entire.png"');
	send_msg($args,$output);
	send_msg($args,'[CQ:image,file=file://'.getcwd().'/dl/temp.png]');
	send_msg($args,'[CQ:image,file=file://'.getcwd().'/dl/temp_entire.png]');
	delete_msg($message_id);

	return;
};


?> 
