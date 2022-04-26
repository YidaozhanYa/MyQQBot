<?php
//custom filter
function filter_hard($args){
	if (strpos($args["command"],"精致睡眠")!==false){
		send_msg($args,"睡不着！");
		return false;
	}
    return true;
};

function filter($command){
    $output=$command;
    $output=str_replace("傻逼","大聪明",$output);
    $output=str_replace("脑瘫","大聪明",$output);
    $output=str_replace("傻比","大聪明",$output);
    $output=str_ireplace("fuck","love",$output);
    $output=str_ireplace("aya爬","aya大帅比",$output);
    return $output;
}
;
?> 
