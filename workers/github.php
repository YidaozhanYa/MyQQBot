<?php
//desc 获取 GitHub 上的用户或仓库信息
//usage <用户>/<仓库>
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
    if (strpos($message, '/') !== false) {
        $message_id=send_msg($args, "正在查询，请稍等片刻 ...");
        error_log(GHAPI_ROOT."repos/".$message);
        $ghapi= json_decode(get_data_github(GHAPI_ROOT."repos/".$message,0,1),true);
        if (is_null($ghapi['documentation_url'])==false and $ghapi['documentation_url']=="https://docs.github.com/rest/overview/resources-in-the-rest-api#rate-limiting"){
            delete_msg($message_id);
            send_msg($args, "本小时 GitHub API 使用超出限制，请过一个小时再试。");
            return;
        };
        $branchapi= json_decode(get_data_github(GHAPI_ROOT."repos/".$message.'/branches',0,1),true);
        $relapi= json_decode(get_data_github(GHAPI_ROOT."repos/".$message.'/releases/latest',0,1),true);
        $commitapi= json_decode(get_data_github(GHAPI_ROOT."repos/".$message.'/commits',0,1),true);
        if ($ghapi['fork']){
            $return=$ghapi['full_name']." (Fork)".PHP_EOL;
        } else {
            $return=$ghapi['full_name'].PHP_EOL;
        };
        $return=$return.$ghapi['description'].PHP_EOL;
        $return=$return.$ghapi['forks'].'Fork '.$ghapi['subscribers_count'].'订阅'.PHP_EOL;
        $return=$return.'分支：';
        foreach ($branchapi as $branch){
            $return=$return.$branch['name']." ";
        };
        $return=$return.PHP_EOL;
        $return=$return.'上次更新：'.str_replace("Z"," ",str_replace("T"," ",get_value($ghapi,"pushed_at"))).PHP_EOL;
        $return=$return.'最新commit：#'.substr($commitapi[0]['sha'],0,6).' ('.$commitapi[0]['message'].')'.PHP_EOL;
        if (get_value($relapi,"name")!=="无或私密") {
            $return=$return.'最新release：'.get_value($relapi,"name").' ('.get_value($relapi,"tag_name").' '.get_value($relapi,"target_commitish").')'.PHP_EOL;
        } else {
            $return=$return.'这个仓库未发布过 Release。'.PHP_EOL;
        };
        delete_msg($message_id);
    } else {
        $message_id=send_msg($args, "正在查询，请稍等片刻 ...");
        error_log(GHAPI_ROOT."users/".$message);
        $ghapi= json_decode(get_data_github(GHAPI_ROOT."users/".$message,0,1),true);
        if (is_null($ghapi['documentation_url'])==false and $ghapi['documentation_url']=="https://docs.github.com/rest/overview/resources-in-the-rest-api#rate-limiting"){
            delete_msg($message_id);
            send_msg($args, "本小时 GitHub API 使用超出限制，请过一个小时再试。");
            return;
        };
        $return=$ghapi['name'].' ('.$ghapi['login'].')'.PHP_EOL;
        $return=$return.$ghapi['bio'].PHP_EOL;
        $return=$return.$ghapi['public_repos'].'个仓库 '.$ghapi['public_gists'].'条Gist'.PHP_EOL;
        $return=$return.$ghapi['following'].'关注 '.$ghapi['followers'].'粉丝'.PHP_EOL;
        $return=$return.'邮箱：'.get_value($ghapi,"email").PHP_EOL;
        $return=$return.'网站：'.get_value($ghapi,"blog").PHP_EOL;
        $return=$return.'推特：'.get_value($ghapi,"twitter_username").PHP_EOL;
        $return=$return.'公司：'.get_value($ghapi,"company").PHP_EOL;
        $return=$return.'位置：'.get_value($ghapi,"location");
    };
    send_msg($args, $return);
    return;
};

function get_value($var,$value){
    if(is_null($var[$value])) {
        return '无或私密';
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