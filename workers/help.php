<?php
function permission(){
	global $allow_user;
	global $allow_group;
	global $deny_user;
	global $deny_group;
};

function msg_handler($args){
    $help="帮助：".PHP_EOL;
    $help=$help.CMD_PREFIX."echo：发送一条消息。<消息内容>".PHP_EOL;
    $help=$help.CMD_PREFIX."arch：查询 ArchLinux 软件包，包括软件库和 AUR。<包名>".PHP_EOL;
    $help=$help.CMD_PREFIX."paste：拉取云剪贴板内容。<剪贴ID>".PHP_EOL;
    $help=$help.CMD_PREFIX."mm2level：查询《超级马里奥制造 2》关卡信息。<关卡ID>".PHP_EOL;
    $help=$help.CMD_PREFIX."mm2maker：查询《超级马里奥制造 2》工匠信息。<关卡ID>".PHP_EOL;
    $help=$help.CMD_PREFIX."hitokoto：一言（hitokoto.cn）。".PHP_EOL;
    $help=$help.CMD_PREFIX."ping：测试网络延迟。<域名/IP>".PHP_EOL;
    $help=$help.CMD_PREFIX."github：获取 GitHub 上的用户或仓库信息。<用户>/<仓库>".PHP_EOL;
    $help=$help.CMD_PREFIX."smmwedl：下载 《SMM:WE》 在线关卡。<关卡ID>".PHP_EOL;
    $help=$help.'Powered by go-cqhttp，2021-2022 是一刀斩哒'
	send_group_msg($args["group_id"],$help);
	return;
};
?>