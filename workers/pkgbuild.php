<?php
//desc 获取 AUR 软件包的 PKGBUILD
//usage <包名>
function permission(){
	global $allow_user;
	global $allow_group;
	global $deny_user;
	global $deny_group;
};

function msg_handler($args){
    $pkgname=strtolower($args['command']);
	$message_id=send_msg($args,"正在查询软件包 ".$pkgname." 的 PKGBUILD ...");
    	//AUR
    	$pkgbuild=get_data("https://aur.archlinux.org/cgit/aur.git/plain/PKGBUILD?h=".$pkgname,0,0);
		if (strpos($pkgbuild,"<!DOCTYPE html>")!==false){
			send_msg($args,$pkgname." 软件包不存在，或不在 AUR 中。");
		} else {
			send_msg_topicture_sh($args,$pkgbuild,"kde");
		};
		delete_msg($message_id);
		return;
};

?> 
