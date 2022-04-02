<?php
function custom_handler($args){
//bilibili
if (substr($args['message'],0,2)=="BV"){
    bv_return_meta(explode(" ",$args['message'])[0],$args,false);
};
if (substr($args['message'],0,5)=="b23.tv"){
    bv_return_meta("https://".b23_to_bvid($args['message']),$args,false);

};
if (substr($args['message'],0,14)=="https://b23.tv"){
    bv_return_meta(b23_to_bvid($args['message']),$args,false);

};
if (substr($args['message'],0,24)=="https://www.bilibili.com"){
    bv_return_meta(str_replace("https://www.bilibili.com/video/","",explode("?",$args['message'])[0]),$args,true);
};
return;
};

function bv_return_meta($bvid,$args,$nourl){
    $bvdata=json_decode(get_data("https://api.bilibili.com/x/web-interface/view?cid=141553944&bvid=".$bvid,0,0),true)['data'];
    $output="[".$bvdata['tname']."] ".$bvdata['title'].PHP_EOL;
    $output=$output.$bvdata['bvid']." av".$bvdata['aid'].PHP_EOL;
    $output=$output."UP: ".$bvdata['owner']['name'].PHP_EOL;
    $output=$output."发布于 ".date('Y-m-d H:i:s',strval($bvdata['pubdate'])).PHP_EOL;
    $output=$output.$bvdata['stat']['like']."点赞 ".$bvdata['stat']['dislike']."点踩 ".$bvdata['stat']['coin']."硬币 ".$bvdata['stat']['favorite']."收藏 ".$bvdata['stat']['share']."分享 ".PHP_EOL;
    $output=$output.$bvdata['stat']['view']."播放 ".$bvdata['stat']['danmaku']."弹幕 ".$bvdata['stat']['reply']."评论 ".PHP_EOL;
    if ($bvdata['stat']['evaluation']!==""){$output=$output.$bvdata['stat']['evaluation'].PHP_EOL;};
    if ($bvdata['stat']['argue_msg']!==""){$output=$output.$bvdata['stat']['argue_msg'].PHP_EOL;};
    $output=$output."简介: ".$bvdata['desc'].PHP_EOL;
    send_msg($args,"[CQ:image,file=".$bvdata["pic"]."]");
    send_msg_topicture($args,$output,"kde");
    if ($nourl==false){
    send_msg($args,"https://www.bilibili.com/video/".$bvdata['bvid']);}
};
function b23_to_bvid($url){
    return explode("?",explode("bilibili.com/video/",get_data($url,0,0))[1])[0];
}
?> 
