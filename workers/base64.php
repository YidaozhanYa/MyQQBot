<?php
//desc BASE64 加密解密
//usage <e或d> <原文或密文>，e加密d解密

function permission(){
	global $allow_user;
	global $allow_group;
	global $deny_user;
	global $deny_group;
};

function msg_handler($args){
	if (substr($args['command'],0,1)=="e") {
		send_msg($args,base64_encode(substr($args["command"],2)));
	} elseif (substr($args['command'],0,1)=="d") {
		send_msg($args,base64_decode(substr($args["command"],2),false));
	} else {
		send_msg($args,'❌ 命令用法不正确。');
	};
};
?>