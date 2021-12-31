<?php
//desc 查看帮助
//usage <命令> 或什么都不加

use function PHPSTORM_META\type;

function permission(){
	global $allow_user;
	global $allow_group;
	global $deny_user;
	global $deny_group;
};

function msg_handler($args){
    $dir=scandir(getcwd()."/workers");
    if (strpos($args['message'],"admin")==false) {
        $help="帮助：".PHP_EOL;
    } else {
        $help="帮助（管理员）：".PHP_EOL;
    };
    foreach ($dir as $cmd) {
        if (strpos($cmd,".php")!==false){
            $cmd_contents=file(getcwd()."/workers/".$cmd,FILE_SKIP_EMPTY_LINES);
            $cmds[2]=false;
            foreach ($cmd_contents as $line){
                if (strpos($line,"//admin")!==false) {
                    $cmds[2]=true;
                    error_log('admin');
                };
                if (strpos($line,"//desc")!==false) {
                    $cmds[0]=CMD_PREFIX.str_replace(".php","",$cmd)."：".str_replace(PHP_EOL,"",str_replace("//desc","",$line));
                };
                if (strpos($line,"//usage")!==false) {
                    $cmds[1]="参数：".str_replace(PHP_EOL,"",str_replace("//usage","",$line));
                    break;
                };
            };
            if (strpos($args['message'],"admin")==false) {
                if (!$cmds[2]) {
                    $help=$help.$cmds[0]."，".$cmds[1].PHP_EOL;
                };
            } else {
                if ($cmds[2]) {
                    $help=$help.$cmds[0]."，".$cmds[1].PHP_EOL;
                };
            };
        };
    };
    $help=$help.'Powered by go-cqhttp，2021-2022 是一刀斩哒';
	send_group_msg($args["group_id"],$help);
	return;
};
?>