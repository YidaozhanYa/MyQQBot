<?php
//desc 将别的语言翻译成中文。
//usage <消息内容>

function permission(){
	global $allow_user;
	global $allow_group;
	global $deny_user;
	global $deny_group;
};

function msg_handler($args){
    $url="http://api.microsofttranslator.com/v2/Http.svc/Translate?appId=AFC76A66CF4F434ED080D245C30CF1E71C22959C&from=&to=zh&text=";
	send_msg($args,str_replace('<string xmlns="http://schemas.microsoft.com/2003/10/Serialization/">',"",str_replace("</string>","",get_data($url . rawurlencode($args['command']),0,1))));
};
?> 
