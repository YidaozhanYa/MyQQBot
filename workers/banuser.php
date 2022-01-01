<?php
//desc 封禁用户的机器人命令
//usage <QQ号> <时长>，单位：秒

function permission(){
	global $allow_user;
	global $allow_group;
	global $deny_user;
	global $deny_group;
	$allow_user=array_merge(array(SUPERADMIN),ADMIN);
};

function msg_handler($args){
	$ban_user=explode(" ",$args['command'])[0];
	$ban_time=explode(" ",$args['command'])[1];
	if ($ban_user!==SUPERADMIN){
		$ban_file='data_store/ban.php';
		if (file_exists($ban_file)) {
			require($ban_file);
		} else {
			$ban_array=array();
		};
		$ban_array[$ban_user]=array(time(),$ban_time);
		$code= "<?php \$ban_array=".var_export($ban_array,true)."; ?>";
		file_put_contents($ban_file, $code);
		send_msg($args,"封禁用户成功。");
	} else {
		send_msg($args,"超级管理员不可封禁。");
	};
	return;
};
?>