<?php
// 加载配置
require("config.php");
global_config();

// 接收消息，调用 msg_handler 函数
global $args;
error_log(file_get_contents("php://input"));
$args=json_decode(file_get_contents("php://input"),true);
$args['message']=str_replace(CMD_PREFIX_BACKUP,CMD_PREFIX,$args['message']);
$cmd_name=str_replace(CMD_PREFIX,"",explode(" ",$args["message"])[0]);

// 频道相关判定
if ($args['message_type']=='guild') {
	if ($args['guild_id']!==GUILD_ID){
		error_log($args['guild_id']);
		return;
	};
	if ($args['channel_id']!==CHANNEL_ID and ENABLE_CHANNEL_ID){
		if (NOPERMIT_CHANNEL_ERROR) {
			send_msg($args,"这个子频道未启用机器人。");
			return;
		} else {
			error_log($args['channel_id']." 子频道未启用机器人。");
			return;
		};
	};
	if (!in_array($cmd_name,ENABLE_GUILD_CMDS)) {
		error_log($cmd_name);
		return;	
	};
};

// 封禁
if(do_ban($args)){return;};

// ？？？？
if (preg_match('/[涩色]图/',$args['message'])) {
	send_msg($args,'没有涩图');
	return;
};
$fname=getcwd()."/workers/".$cmd_name.".php";
if(file_exists($fname)) {
	$args['command']=str_replace(explode(" ",$args["message"])[0],"",$args["message"]);
	do {
		$args['command']=substr($args['command'],1);
	} while (substr($args['command'],0,1)==" ");
	require($fname);
} else {
	if (CMDNOTFOUND_ERROR) {
	send_msg($args,"命令未找到。");
	} else {
		error_log("命令未找到。".$args['message']);
	};
	return;
};

// 用户权限
global $allow_user;
global $allow_group;
global $deny_user;
global $deny_group;
permission();
global_permission();
// allow 和 deny 不能同时使用
if (is_null($allow_user)==false and is_null($deny_user)==false) {
	error_log("命令权限设置错误");
	return;
};
if (is_null($allow_group)==false and is_null($deny_group)==false) {
	error_log("命令权限设置错误");
	return;
};
if (is_null($allow_user)==false and in_array($args['user_id'],$allow_user,true)==false) {
	if (NOPERMIT_USER_ERROR) {
		send_msg($args,"您没有使用此命令的权限。");
	} else {
		error_log($args["group_id"],"您没有使用此命令的权限。");
	};
	return;
};
if (is_null($deny_user)==false and in_array($args['user_id'],$deny_user,true)==true) {
	if (NOPERMIT_USER_ERROR) {
		send_msg($args,"您没有使用此命令的权限。");
	} else {
		error_log($args["group_id"],"您没有使用此命令的权限。");
	};
	return;
};
if ($args['message_type']=='group' and is_null($allow_group)==false and in_array($args['group_id'],$allow_group,true)==false) {
	if (NOPERMIT_GROUP_ERROR) {
		send_group_msg($args["group_id"],$args['group_id']." 群组未启用机器人。");
	} else {
		error_log($args['group_id']." 群组未启用机器人。");
	};
	return;
};
if ($args['message_type']=='group' and is_null($deny_group)==false and in_array($args['group_id'],$deny_group,true)==true) {
	if (NOPERMIT_GROUP_ERROR) {
		send_group_msg($args["group_id"],$args['group_id']." 群组未启用机器人。");
	} else {
		error_log($args['group_id']." 群组未启用机器人。");
	};
	return;
};
msg_handler($args);
return;

// 以下函数可在各个命令内调用

//调用 go-cqhttp 的 API （GET 方法）
function cqhttp_api($api,$get_data){
	$curl=curl_init();
	//curl_setopt($curl,CURLOPT_HEADER,0);
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
	$datastr='http://127.0.0.1:'.strval(CQHTTP_PORT)."/".$api.'?';
	foreach ($get_data as $k=>$d){
	$datastr=$datastr.rawurlencode($k).'='.rawurlencode($d).'&';
	};
	$datastr=substr($datastr, 0, -1);
	curl_setopt($curl,CURLOPT_URL,$datastr);
	error_log($datastr);
	$return_data=curl_exec($curl);
	curl_close($curl);
	error_log(json_encode($return_data));
	return json_decode($return_data,true)['data'];
	};

//调用 go-cqhttp 的 API （POST 方法）
function cqhttp_api_post($api,$get_data,$post_data){
	$curl=curl_init();
	//curl_setopt($curl,CURLOPT_HEADER,0);
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($curl,CURLOPT_POST,1);
	curl_setopt($curl,CURLOPT_POSTFIELDS,$post_data);
	$datastr='http://127.0.0.1:'.strval(CQHTTP_PORT)."/".$api.'?';
	foreach ($get_data as $k=>$d){
	$datastr=$datastr.rawurlencode($k).'='.rawurlencode($d).'&';
	};
	$datastr=substr($datastr, 0, -1);
	curl_setopt($curl,CURLOPT_URL,$datastr);
	error_log($datastr);
	$return_data=curl_exec($curl);
	curl_close($curl);
	error_log(json_encode($return_data));
	return json_decode($return_data,true)['data'];
	};

// 发送群消息（API 封装）
function send_group_msg($group_id,$message){
	return intval(cqhttp_api("send_msg",array("message_type"=>"group","group_id"=>intval($group_id),"message"=>$message))['message_id']);
};

// 根据消息类型发送消息（API 封装）
function send_msg($args,$message){
	if ($args['message_type']=='group'){
		return intval(cqhttp_api("send_group_msg",array("group_id"=>intval($args['group_id']),"message"=>$message))['message_id']);
	} elseif ($args['message_type']=='private') {
		return intval(cqhttp_api("send_private_msg",array("user_id"=>intval($args['user_id']),"message"=>$message))['message_id']);
	} elseif ($args['message_type']=='guild') {
		return intval(cqhttp_api("send_guild_channel_msg",array("guild_id"=>$args['guild_id'],"channel_id"=>$args['channel_id'],"message"=>$message))['message_id']);
	};
};

// 发送群合并转发消息（API 封装）
function send_group_forward_msg($group_id,$message){
};

// 撤回消息（API 封装）
function delete_msg($message_id){
	cqhttp_api("delete_msg",array("message_id"=>$message_id));
	return;
};

// 发送群文件（API 封装）
function upload_group_file($group_id,$file,$name){
	return intval(cqhttp_api("upload_group_file",array("group_id"=>intval($group_id),"file"=>$file,"name"=>$name))['message_id']);
};


// cURL GET 方法获取数据
function get_data($url, $enable_header, $follow_location){
	$curl=curl_init();
	curl_setopt($curl,CURLOPT_HEADER,$enable_header);
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($curl,CURLOPT_FOLLOWLOCATION,$follow_location);
	curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, false);
	//curl_setopt($curl,CURLOPT_CONNECTTIMEOUT,60);
	//curl_setopt($curl,CURLOPT_TIMEOUT,60);
	curl_setopt($curl,CURLOPT_URL,$url);
	$return_data=curl_exec($curl);
	curl_close($curl);
	return $return_data;
};

// cURL POST 方法获取数据
function post_data($url, $enable_header, $follow_location, $postfields){
	$curl=curl_init();
	curl_setopt($curl,CURLOPT_HEADER,$enable_header);
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($curl,CURLOPT_FOLLOWLOCATION,$follow_location);
	curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($curl, CURLOPT_POST,1);
	//curl_setopt($curl,CURLOPT_CONNECTTIMEOUT,60);
	//curl_setopt($curl,CURLOPT_TIMEOUT,60);
	curl_setopt($curl,CURLOPT_URL,$url);
	curl_setopt($curl,CURLOPT_POSTFIELDS,$postfields);
	$return_data=curl_exec($curl);
	curl_close($curl);
	return $return_data;
};

// 命令冷却系统
// 调用方法为：在你想触发冷却的地方加一行 if (do_cooldown('冷却ID str',冷却秒数 int,$args)) {return;};
function do_cooldown($cooldown_id,$cooldown_time,$args){
	$cooldown_file='data_store/cooldown_'.$cooldown_id.'.php';
	error_log('执行命令冷却，本地文件为 '.$cooldown_file);
	if (file_exists($cooldown_file)) {
		require($cooldown_file);
	} else {
		$cooldown_array=array();
	};
	if (is_null($cooldown_array[$args['user_id']])) {
		$cooldown_array[$args['user_id']]=time();
		$return= false;
	} else {
		$time_pass=intval(time())-intval($cooldown_array[$args['user_id']]);
		error_log($time_pass);
		if ($time_pass<$cooldown_time) {
			$msgid=send_msg($args,"该命令还在冷却，剩余 ".strval($cooldown_time-$time_pass)." 秒。");
			sleep(1);
			delete_msg($msgid);
			$return= true;
		} else {
			$cooldown_array[$args['user_id']]=time();
			$return= false;
		};
	};
$code= "<?php \$cooldown_array=".var_export($cooldown_array,true)."; ?>";
file_put_contents($cooldown_file, $code);
return $return;
};

// 封禁系统
function do_ban($args){
	$ban_file='data_store/ban.php';
	if (file_exists($ban_file)) {
		require($ban_file);
	} else {
		return false;
	};
	if (is_null($ban_array[$args['user_id']])==false) {
		if ((time()-$ban_array[$args['user_id']][1])<$ban_array[$args['user_id']][0]) {
			$msgid=send_msg($args,"用户已经封禁，距离解封还剩 ".strval($ban_array[$args['user_id']][1]-(time()-$ban_array[$args['user_id']][0]))." 秒。");
			sleep(1);
			delete_msg($msgid);
			$return= true;
		} else {
			$return= false;
		};
	};
return $return;
};

?>