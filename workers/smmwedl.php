<?php
define('apariencia', array('超马1', '超马3', '超马世界', '新超马U', '超马3D世界'));
define('theme', array('Castle' => '城堡', 'Airship' => '飞行船', 'Ghost house' => '鬼屋', 'Underground' => '地下', 'Sky' => '天空', 'Snow' => '雪原', 'Desert' => '沙漠', 'Overworld' => '平原', 'Forest' => '丛林', 'Underwater' => '水中'));
function permission()
{
    global $allow_user;
    global $allow_group;
    global $deny_user;
    global $deny_group;
};

function msg_handler($args)
{
    if (do_cooldown('we',6000,$args)) {return;};
    error_log($args["message"]);
    $lvl_id = strtoupper(str_replace(CMD_PREFIX . "smmwedl ", "", $args['message']));
    if (strpos($lvl_id, "-") == false or strlen($lvl_id) !== 19) {
        send_group_msg($args["group_id"], "关卡 ID 格式不正确。格式：XXXX-XXXX-XXXX-XXXX");
        return;
    };
    $message_id = send_group_msg($args["group_id"], "正在查询关卡 " . $lvl_id . " ...");
    $auth_code = file_get_contents("data_store/smmwe_auth_code.txt");
    $url = "http://172.93.102.10:25833/stage/" . $lvl_id;
    $data = json_decode(post_data($url, 0, 0, "token=30204864&discord_id=" . SMMWE_DISCORDID . "&auth_code=" . $auth_code), true);
    error_log(json_encode($data));
    if (is_null($data['error_type']) == false and $data['error_type'] = '013') {
        $auth_code = json_decode(post_data("http://172.93.102.10:25833/user/login", 0, 0, "token=30204864&alias=" . SMMWE_ACCOUNT . "&password=" . SMMWE_PASSWD), true)['auth_code'];
        file_put_contents("data_store/smmwe_auth_code.txt", $auth_code);
        $data = json_decode(post_data($url, 0, 0, "token=30204864&discord_id=" . SMMWE_DISCORDID . "&auth_code=" . $auth_code), true);
        error_log(json_encode($data));
    };
    if (is_null($data['error_type']) == false and $data['error_type'] = '029') {
        send_group_msg($args["group_id"], "关卡 ".$lvl_id." 不存在。");
        delete_msg($message_id);
        return;
    };
    $data = $data['result'];
    $return = "";
    $return = $return . '关卡：' . $data['name'] . PHP_EOL;
    $return = $return . '作者：' . $data['author'] . PHP_EOL;
    $return = $return .apariencia[$data['apariencia']] . ' ' . $data['etiquetas'] . PHP_EOL;
    $return = $return . $data['likes'] . '赞 ' . $data['dislikes'] .'孬' . PHP_EOL;
    $return = $return . $data['intentos'] . '游玩 ' . $data['victorias'] .'通过 ' . $data['muertes'] .'死亡 ' .number_format(($data['victorias']/$data['intentos']),2)."%".PHP_EOL;
    $return = $return . '上传日期：' . $data['date'] ;
    if ($data['record']['record']=="yes"){
        $return = $return .PHP_EOL. '纪录：' . $data['record']['alias'];
    };
    $lvl_file=get_data($data['archivo'],0,0);
    file_put_contents("data_store/smmwe.swe",$lvl_file);
    upload_group_file($args["group_id"],"data_store/smmwe.swe",$data['name']." ".$lvl_id.".swe");
    send_group_msg($args["group_id"], $return);
    delete_msg($message_id);

    return;
};
