<?php
//desc 机器人管理相关
//usage <保密>

function permission(){
	global $allow_user;
	global $allow_group;
	global $deny_user;
	global $deny_group;
	$allow_user=array(SUPERADMIN);
};

function msg_handler($args){
	$subcmd=explode(" ",$args['command'])[0];
	switch ($subcmd){
		case "send_group_msg":
			$group_id=explode(" ",str_replace("send_group_msg ","",$args['command']))[0];
			$message=str_replace("send_group_msg ".$group_id." ","",$args['command']);
			send_group_msg($group_id,$message);
			send_msg($args,"✅ 发送成功。");
			break;
		case "unban":
			unlink(getcwd()."/data_store/ban.php");
			send_msg($args,"♻️ 已经解除封禁所有用户。");
			break;
		case "clear_cd":
			unlink(getcwd()."/data_store/cooldown_".str_replace("uncd ","",$args['command']).".php");
			send_msg($args,"♻️ 重置了 ".str_replace("uncd ","",$args['command'])." 冷却 ID。");
			break;
		case "clear_cache":
			unlink(getcwd()."/data_store/pkgbuild.sh");
			unlink(getcwd()."/data_store/temp.sh");
			unlink(getcwd()."/data_store/temp.txt");
			unlink(getcwd()."/data_store/full.txt");
			unlink(getcwd()."/data_store/images/temp.png");
			send_msg($args,"📤 已经清空缓存。");
			break;
		case "grass":
			send_msg($args,"草");
			break;
		case "ls":
			send_msg($args,"send_group_msg unban clear_cd clear_cache grass");
			break;
		default:
			send_msg($args,"❎ 未找到命令。");
	};
};
?>