<?php
//desc 美国搬起石头砸自己的脚
//usage <名字？>

function permission(){
	global $allow_user;
	global $allow_group;
	global $deny_user;
	global $deny_group;
};

function msg_handler($args){
	$america=$args['command'];
	if (($args['message_type']=="group") and ($args['group_id']!==920064067)) {if (do_cooldown('fabing',3600,$args)) {return;};}
	send_msg($args,"${america}不肯承认自己错误的做法，反而使用控制舆论等方式试图掩盖自己的行为。${america}这种卑劣行径，恰恰暴露了${america}做贼心虚的心理。${america}这种认不清自己情况，糊弄民众，透支未来的行为，到最后一定是搬起石头砸自己的脚！${america}的这种错误行为，只会在错误的道路上越走越远！");
};
?>