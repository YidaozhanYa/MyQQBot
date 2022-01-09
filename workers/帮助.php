<?php
//desc 查看帮助
//usage <分类> 或什么都不加

function permission(){
	global $allow_user;
	global $allow_group;
	global $deny_user;
	global $deny_group;
};

function msg_handler($args){
    if ($args['message_type']=='group') {
        if (do_cooldown('help',60,$args)) {return;};
    };
    if ($args['message_type']=='guild') {
        $help="帮助（频道）：".PHP_EOL;
        foreach (ENABLE_GUILD_CMDS as $cmd) {
            if (!$cmd=='帮助') {
            $cmd_contents=file(getcwd()."/workers/".$cmd.".php",FILE_SKIP_EMPTY_LINES);
            $cmds[2]=false;
            foreach ($cmd_contents as $line){
                if (strpos($line,"//desc")!==false) {
                    $cmds[0]=CMD_PREFIX.$cmd."：".str_replace(PHP_EOL,"",str_replace("//desc","",$line));
                };
                if (strpos($line,"//usage")!==false) {
                    $cmds[1]="参数：".str_replace(PHP_EOL,"",str_replace("//usage","",$line));
                    break;
                };
            };
                $help=$help.$cmds[0]."，".$cmds[1].PHP_EOL;
        }
        };
        $help=$help.'Powered by go-cqhttp，2022 是一刀斩哒';
        error_log($help);
    } else {
    if ($args['command']=="") {
        $help="帮助：".PHP_EOL;
        foreach(CMDLIST as $key=>$cmd){
            $help=$help.$key.'：'.$cmd[0].PHP_EOL;
        };
        $help=$help.'Tips：请不要在群内利用帮助刷屏，尽量使用私信。'.PHP_EOL.'使用 ::帮助 <分类> 查看不同分类的帮助。'.PHP_EOL.'Powered by go-cqhttp，2021-2022 是一刀斩哒';
    } else {
        if (!is_null(CMDLIST[$args['command']])) {
        $help="帮助（".CMDLIST[$args['command']][0]."）：".PHP_EOL;
        foreach (CMDLIST[$args['command']][1] as $key=>$cmd) {
            $cmd_contents=file(getcwd()."/workers/".$cmd.".php",FILE_SKIP_EMPTY_LINES);
            $cmds[2]=false;
            foreach ($cmd_contents as $line){
                if (strpos($line,"//desc")!==false) {
                    $cmds[0]=CMD_PREFIX.$cmd."：".str_replace(PHP_EOL,"",str_replace("//desc","",$line));
                };
                if (strpos($line,"//usage")!==false) {
                    $cmds[1]="参数：".str_replace(PHP_EOL,"",str_replace("//usage","",$line));
                    break;
                };
            };
                $help=$help.$cmds[0]."，".$cmds[1].PHP_EOL;
        };
        } else {
        $help='没有这个分类。'.PHP_EOL;
        };
        $help=$help.'Powered by go-cqhttp，2022 是一刀斩哒';
    };
    };
	send_msg($args,$help);
	return;
};
?>