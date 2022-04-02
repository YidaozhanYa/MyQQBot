<?php
//desc 获取 GitHub 上的用户或仓库信息
//usage <用户>（/<仓库>）
function permission()
{
    global $allow_user;
    global $allow_group;
    global $deny_user;
    global $deny_group;
};

function msg_handler($args)
{
    define("GHAPI_ROOT", "https://api.github.com/");
    $message = $args['command'];
    $message_id=send_msg($args, "⏰ 正在查询，请稍等片刻 ...");
    if (strpos($message, '/') !== false) {
        error_log(GHAPI_ROOT."repos/".$message);
        $ghapi= json_decode(get_data_github(GHAPI_ROOT."repos/".$message,0,1),true);
        if (is_null($ghapi['documentation_url'])==false and $ghapi['documentation_url']=="https://docs.github.com/rest/overview/resources-in-the-rest-api#rate-limiting"){
            delete_msg($message_id);
            send_msg($args, "⛔ 本小时 GitHub API 使用超出限制，请过一个小时再试。");
            return;
        };
        $branchapi= json_decode(get_data_github(GHAPI_ROOT."repos/".$message.'/branches',0,1),true);
        $relapi= json_decode(get_data_github(GHAPI_ROOT."repos/".$message.'/releases/latest',0,1),true);
        $commitapi= json_decode(get_data_github(GHAPI_ROOT."repos/".$message.'/commits',0,1),true);
        if ($ghapi['fork']){
            $return="👤 ".$ghapi['owner']['login']." / 🌐 ".$ghapi['name']." (🍴)".PHP_EOL;
        } else {
            $return="👤 ".$ghapi['owner']['login']." / 🌐 ".$ghapi['name'].PHP_EOL;
        };
        $return=$return."📜 - ".$ghapi['description'].PHP_EOL;
        $return=$return.$ghapi['stargazers_count'].' ⭐   '.$ghapi['forks'].' 🍴   '.$ghapi['subscribers_count']." 👁".PHP_EOL;
        $return=$return.'📋 - ';
        foreach ($branchapi as $branch){
            $return=$return.$branch['name']."  ";
        };
        $return=$return.PHP_EOL;
        $return=$return.'📤 - '.str_replace("Z"," ",str_replace("T"," ",get_value($ghapi,"pushed_at"))).PHP_EOL;
        $return=$return.'✅ - #'.substr($commitapi[0]['sha'],0,6).' ('.$commitapi[0]['commit']['message']." 👤 ".$commitapi[0]['commit']['author']['name'].')'.PHP_EOL;
        if (get_value($relapi,"name")!=="🈚") {
            $return=$return.'📦 - '.get_value($relapi,"name").' ('.get_value($relapi,"tag_name").' '.get_value($relapi,"target_commitish").')';
        } else {
            $return=$return.'📦 - 🈚';
        };
    } else {
        error_log(GHAPI_ROOT."users/".$message);
        $ghapi= json_decode(get_data_github(GHAPI_ROOT."users/".$message,0,1),true);
        if (is_null($ghapi['documentation_url'])==false and $ghapi['documentation_url']=="https://docs.github.com/rest/overview/resources-in-the-rest-api#rate-limiting"){
            delete_msg($message_id);
            send_msg($args, "⛔ 本小时 GitHub API 使用超出限制，请过一个小时再试。");
            return;
        };
        $return="👤 ".$ghapi['name'].' ('.$ghapi['login'].')'.PHP_EOL;
        $return=$return."📜 - ".$ghapi['bio'].PHP_EOL;
        $return=$return.$ghapi['public_repos'].' 🗂   '.$ghapi['public_gists'].' 📝   '.$ghapi['following'].' 👁   '.$ghapi['followers'].' 👥'.PHP_EOL;
        $return=$return.'📫 - '.get_value($ghapi,"email").PHP_EOL;
        $return=$return.'🏠 - '.get_value($ghapi,"blog").PHP_EOL;
        $return=$return.'🐦 - '.get_value($ghapi,"twitter_username").PHP_EOL;
        $return=$return.'🏢 - '.get_value($ghapi,"company").PHP_EOL;
        $return=$return.'🗺 - '.get_value($ghapi,"location");
    };
    delete_msg($message_id);
    if (strlen($return)>700){
        send_msg_topicture($args, $return,"kde");
    } else {
        send_msg($args, $return);
    };
    return;
};

function get_value($var,$value){
    if(is_null($var[$value])) {
        return '🈚';
    } else {
        return $var[$value];
    };
};

function get_data_github($url, $enable_header, $follow_location){
	$curl=curl_init();
	curl_setopt($curl,CURLOPT_HEADER,$enable_header);
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($curl,CURLOPT_FOLLOWLOCATION,$follow_location);
	curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($curl,CURLOPT_USERAGENT,'YidaozhanYaQQBot');
    curl_setopt($curl,CURLOPT_USERPWD,'YidaozhanYa:'.GITHUB_TOKEN);
	//curl_setopt($curl,CURLOPT_CONNECTTIMEOUT,60);
	//curl_setopt($curl,CURLOPT_TIMEOUT,60);
	curl_setopt($curl,CURLOPT_URL,$url);
	$return_data=curl_exec($curl);
	curl_close($curl);
	return $return_data;
};