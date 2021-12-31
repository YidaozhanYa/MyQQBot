<?php
//admin
//desc 封禁用户的机器人命令
//usage <QQ号> <时长>，单位：秒

function permission(){
	global $allow_user;
	global $allow_group;
	global $deny_user;
	global $deny_group;
	$allow_user=array(3526514925);
};

function msg_handler($args){
	$ban_user=explode(" ",str_replace(CMD_PREFIX."banuser ","",$args['message']))[0];
	$ban_time=explode(" ",str_replace(CMD_PREFIX."banuser ","",$args['message']))[1];
	error_log($args["message"]);
	$ban_file='data_store/ban.php';
	if (file_exists($ban_file)) {
		require($ban_file);
	} else {
		$ban_array=array();
	};
	$ban_array[$ban_user]=array(time(),$ban_time);
	$code= "<?php \$ban_array=".var_export($ban_array,true)."; ?>";
	file_put_contents($ban_file, $code);
	send_group_msg($args["group_id"],"设置成功。");
	return;
};
?>