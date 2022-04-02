<?php
//desc 随机兽耳酱图片 (复刻自 Ayatale)
//usage <无参数>

function permission(){
	global $allow_user;
	global $allow_group;
	global $deny_user;
	global $deny_group;
};

function msg_handler($args){
	send_msg($args,strval("[CQ:image,file=https://ayatale.coding.net/p/picbed/d/kemo/git/raw/master/".rand(1,696).".jpg]"));
};
?>