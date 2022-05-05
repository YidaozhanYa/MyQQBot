<?php
//desc 对着老婆发病
//usage <名字？>

function permission(){
	global $allow_user;
	global $allow_group;
	global $deny_user;
	global $deny_group;
};

function msg_handler($args){
	$laopo=$args['command'];
	if (($args['message_type']=="group") and ($args['group_id']!==920064067)) {if (do_cooldown('fabing',3600,$args)) {return;};}
	send_msg($args,"${laopo}……🤤嘿嘿………🤤……好可爱……嘿嘿……${laopo}🤤……${laopo}……我的🤤……嘿嘿……🤤………亲爱的……赶紧让我抱一抱……啊啊啊${laopo}软软的脸蛋🤤还有软软的小手手……🤤…${laopo}……不会有人来伤害你的…🤤你就让我保护你吧嘿嘿嘿嘿嘿嘿嘿嘿🤤……太可爱了……🤤……美丽可爱的${laopo}……像珍珠一样……🤤嘿嘿……${laopo}……🤤嘿嘿……🤤……好想一口吞掉……🤤……但是舍不得啊……我的${laopo}🤤……嘿嘿……🤤我的宝贝……我最可爱的${laopo}……🤤没有${laopo}……我就要死掉了呢……🤤我的……🤤嘿嘿……可爱的${laopo}……嘿嘿🤤……可爱的${laopo}……嘿嘿🤤🤤……可爱的${laopo}……🤤……嘿嘿🤤……可爱的${laopo}…（吸）身上的味道……好好闻～🤤…嘿嘿🤤……摸摸～……可爱的${laopo}……再贴近我一点嘛……（蹭蹭）嘿嘿🤤……可爱的${laopo}……嘿嘿🤤……～亲一口～……可爱的${laopo}……嘿嘿🤤……抱抱你～可爱的${laopo}～（舔）喜欢～真的好喜欢～……（蹭蹭）脑袋要融化了呢～已经……除了${laopo}以外～什么都不会想了呢～🤤嘿嘿🤤……可爱的${laopo}……嘿嘿🤤……可爱的${laopo}……我的～……嘿嘿🤤……");
};
?>