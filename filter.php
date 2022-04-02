<?php
//custom filter
function filter_hard($args){
    if ($args['user_id']=2224740464 and strpos($args['command'],"aya")!==false) {
        send_msg($args,"爬！");
        return false;
    } else {
    return true;
    }
};

//function filter($args){

    //return $output;
//};
?> 
