<?php
//desc 下载 《SMM:WE》 在线关卡
//usage <关卡ID>，带横杠
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
    if ($args['message_type'] == 'group') {
        //if (do_cooldown('we', 6000, $args)) {
        //    return;
        //};
    };
    $lvl_id = strtoupper($args['command']);
    if (strpos($lvl_id, "-") == false or strlen($lvl_id) !== 19) {
        send_msg($args, "关卡 ID 格式不正确。格式：XXXX-XXXX-XXXX-XXXX");
        return;
    };
    $message_id = send_msg($args, "正在查询关卡 " . $lvl_id . " ...");
    $auth_code = file_get_contents("data_store/smmwe_auth_code.txt");
    $url = "http://172.93.102.10:25833/stage/" . $lvl_id;
    $data = json_decode(post_data($url, 0, 0, "token=30204864&discord_id=" . SMMWE_DISCORDID . "&auth_code=" . $auth_code), true);
    error_log(json_encode($data));
    if ($data['error_type'] === '015' or $data['error_type'] === '013') {
        error_log("relogin");
        $auth_code=post_data("http://172.93.102.10:25833/user/login", 0, 0, "token=30204864&alias=" . SMMWE_ACCOUNT . "&password=" . SMMWE_PASSWD);
        error_log($auth_code);
        if (json_decode($auth_code,true)["error_type"]==="008") {
            send_msg($args, "发生未知错误。屑弗！");
            delete_msg($message_id);
            return;
        };
        $auth_code = json_decode($auth_code,true)['auth_code'];
        file_put_contents("data_store/smmwe_auth_code.txt", $auth_code);
        $data = json_decode(post_data($url, 0, 0, "token=30204864&discord_id=" . SMMWE_DISCORDID . "&auth_code=" . $auth_code), true);
        error_log(json_encode($data));
    };
    if ($data['error_type'] === '029') {
        send_msg($args, "关卡 " . $lvl_id . " 不存在。");
        delete_msg($message_id);
        return;
    };
    $data = $data['result'];
    $return = "";
    $return = $return . '关卡：' . $data['name'] . PHP_EOL;
    $return = $return . '作者：' . $data['author'] . PHP_EOL;
    $return = $return . apariencia[$data['apariencia']] . ' ' . $data['etiquetas'] . PHP_EOL;
    $return = $return . $data['likes'] . '赞 ' . $data['dislikes'] . '孬' . PHP_EOL;
    $return = $return . $data['intentos'] . '游玩 ' . $data['victorias'] . '通过 ' . $data['muertes'] . '死亡 ' . number_format(($data['victorias'] / $data['intentos']), 2) . "%" . PHP_EOL;
    $return = $return . '上传日期：' . $data['date'];
    if ($data['record']['record'] == "yes") {
        $return = $return . PHP_EOL . '纪录：' . $data['record']['alias'];
    };
    send_msg($args, $return);
    if ($args['message_type'] == 'group') {
        $lvl_file = get_data($data['archivo'], 0, 0);
        file_put_contents("data_store/smmwe.swe", $lvl_file);
        upload_group_file($args["group_id"], "data_store/smmwe.swe", $data['name'] . " " . $lvl_id . ".swe");
        delete_msg($message_id);
        return;
    } else {
        send_msg($args, "注：发送关卡的功能只能在群里使用，因为机器人没有权限发送私聊文件。");
        return;
    };
};
