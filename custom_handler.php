<?php
function custom_handler($args)
{
    error_log($args['message'] . " via custom handler");
    if (substr($args['message'], 0, 2) == "BV") {
        bv_return_meta(explode(" ", $args['message'])[0], $args, false);
        return;
    };
    if (substr($args['message'], 0, 5) == "b23.tv") {;
        bv_return_meta("https://" . b23_to_bvid($args['message']), $args, false);
        return;
    };
    if (substr($args['message'], 0, 14) == "https://b23.tv") {
        bv_return_meta(b23_to_bvid($args['message']), $args, false);
        return;
    };
    if (substr($args['message'], 0, 24) == "https://www.bilibili.com") {
        bv_return_meta(str_replace("https://www.bilibili.com/video/", "", explode("?", $args['message'])[0]), $args, true);
        return;
    };
    if (strpos($args['message'], "QQ小程序&#93;哔哩哔哩") !== false) {
        $strjson = substr(substr($args['message'], strpos($args["message"], "CQ:json,data=")), 13);
        $strjson = str_replace(";", ",", str_replace("&#44", "", substr($strjson, 0, strlen($strjson) - 1)));
        $output = explode("?", json_decode($strjson, true)['meta']['detail_1']['qqdocurl'])[0];
        send_msg($args, "链接: " . $output);
        return;
    };
    if ((strpos($args['message'], "设精") !== false) and (in_array($args['user_id'],array_merge(array(SUPERADMIN),ADMIN))) and (strpos($args['message'], "reply") !== false)) {
        preg_match_all("/\[CQ:reply.*?\]/", $args['message'], $cqcode);
        preg_match_all("/id=.*?]/", $cqcode[0][0], $cqcode);
        $origargs = cqhttp_api("get_msg", array("message_id" => substr($cqcode[0][0], 3, strlen($cqcode[0][0]) - 4)));
        $message = $origargs['message'];
        if (strpos($message, "CQ:image") !== false) {
            $message_id = send_msg($args, "正在把这张图片上传到「一刀斩の小窝」...");
            preg_match_all("/url=.*?]/", $message, $cqcode_img);
            $fname = getcwd() . "/temp/" . $origargs['sender']['user_id'] . "-" . date("d_His", time()) . ".jpg";
            $cmd = "curl \"" . substr($cqcode_img[0][0], 4, strlen($cqcode_img[0][0]) - 5) . "\" -o \"" . $fname . "\"";
            error_log($cmd);
            exec($cmd);
            sleep(1);
            $cmd = "curl \"" . ONEMANAGER_ROOT . date("Y/m", time()) . "/?action=upsmallfile" . "\" -F \"file1=@" . $fname . "\" -H \"Cookie: admin=" . ONEMANAGER_ADMIN . "\"";
            error_log($cmd);
            exec($cmd, $retval);
            $fileurl = json_decode($retval[0], true)['url'];
            send_msg($args, "⤴️ 图片上传成功！" . PHP_EOL . "📥直链: " . $fileurl . PHP_EOL . "🖼 查看: " . $fileurl . "?preview");
            delete_msg($message_id);
            unlink($fname);
        } else {
            $message_id = send_msg($args, "正在把这段话上传到「一刀斩の小窝」...");
            $ext = "txt";
            $fname = getcwd() . "/temp/" . $origargs['sender']['user_id'] . "-" . date("d_His", time()) . "." . $ext;
            file_put_contents($fname, $origargs['sender']['nickname']." (".$origargs['sender']['user_id']."): ".$message);
            $cmd = "curl \"" . ONEMANAGER_ROOT . date("Y/m", time()) . "/?action=upsmallfile" . "\" -F \"file1=@" . $fname . "\" -H \"Cookie: admin=" . ONEMANAGER_ADMIN . "\"";
            error_log($cmd); 
            exec($cmd, $retval);
            $fileurl = json_decode($retval[0], true)['url'];
            send_msg($args, "⤴️ ".$origargs['sender']['nickname'].":「" . Cut_string($message, 0, 10) . "」上传成功！" . PHP_EOL . "📥直链: " . $fileurl . PHP_EOL . "🖼 查看: " . $fileurl . "?preview");
            delete_msg($message_id);
            unlink($fname);
        };
        return;
    };
};

function bv_return_meta($bvid, $args, $nourl)
{
    $bvdata = json_decode(get_data("https://api.bilibili.com/x/web-interface/view?cid=141553944&bvid=" . $bvid, 0, 0), true)['data'];
    $output = "[" . $bvdata['tname'] . "] " . $bvdata['title'] . PHP_EOL;
    $output = $output . $bvdata['bvid'] . " av" . $bvdata['aid'] . PHP_EOL;
    $output = $output . "🆙: " . $bvdata['owner']['name'] . PHP_EOL;
    $output = $output . "⏰ " . date('Y-m-d H:i:s', strval($bvdata['pubdate'])) . PHP_EOL;
    $output = $output . $bvdata['stat']['like'] . "👍  " . $bvdata['stat']['dislike'] . "👎  " . $bvdata['stat']['coin'] . "💸  " . $bvdata['stat']['favorite'] . "⭐  " . $bvdata['stat']['share'] . "⤴" . PHP_EOL;
    $output = $output . $bvdata['stat']['view'] . "▶  " . $bvdata['stat']['danmaku'] . "📨  " . $bvdata['stat']['reply'] . "📃" . PHP_EOL;
    if ($bvdata['stat']['evaluation'] !== "") {
        $output = $output . $bvdata['stat']['evaluation'] . PHP_EOL;
    };
    if ($bvdata['stat']['argue_msg'] !== "") {
        $output = $output . $bvdata['stat']['argue_msg'] . PHP_EOL;
    };
    $output = $output . PHP_EOL . $bvdata['desc'] . PHP_EOL;
    send_msg($args, "[CQ:image,file=" . $bvdata["pic"] . "]");
    //send_msg($args,$output);
    if ($nourl == false) {
        send_msg($args, $output . PHP_EOL . "https://www.bilibili.com/video/" . $bvdata['bvid']);
    } else {
        send_msg($args, $output);
    }
};
function b23_to_bvid($url)
{
    return explode("?", explode("bilibili.com/video/", get_data($url, 0, 0))[1])[0];
}
