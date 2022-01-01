<?php
//desc 查询 ArchLinux 软件包，包括软件库和 AUR
//usage <包名>
function permission(){
	global $allow_user;
	global $allow_group;
	global $deny_user;
	global $deny_group;
};

function msg_handler($args){
    $pkgname=strtolower($args['command']);
	$url="https://archlinux.org/packages/search/json/?name=".$pkgname;
    $pkgarr=json_decode(get_data($url,0,0),true);
    if ($pkgarr['results']==array()) {
    	//AUR
    	$url="https://aur.archlinux.org/rpc/?v=5&type=info&arg=".$pkgname;
    	$pkgarr=json_decode(get_data($url,0,0),true);
   	 if ($pkgarr['resultcount']==0) {
	    	send_msg($args,'错误：查无此包。');
	    	return;
		};
		error_log(json_encode($pkgarr));
		$pkgarr=$pkgarr['results'][0];
		$output="";
		$output=$output."AUR 软件包：".$pkgname." (v".$pkgarr['Version'].")".PHP_EOL;
		$output=$output."简介：".$pkgarr['Description'].PHP_EOL;
		if (is_null($pkgarr['URL'])){
			$output=$output."上游：(无)".PHP_EOL;
		} else {
			$output=$output."上游：".$pkgarr['URL'].PHP_EOL;
		};
		$output=$output."维护者：".$pkgarr['Maintainer'].PHP_EOL;
		$output=$output.get_value($pkgarr,"Depends","依赖","，");
		$output=$output.get_value($pkgarr,"MakeDepends","编译依赖","，");
		$output=$output.get_value($pkgarr,"OptDepends","可选依赖",PHP_EOL);
		$output=$output.get_value($pkgarr,"Conflicts","冲突","，");
		$output=$output.get_value($pkgarr,"Provides","提供","，");
		send_msg($args],$output);
		return;
	};
    error_log(json_encode($pkgarr));
    $pkgarr=$pkgarr['results'][0];
	$output="";
	$output=$output."仓库软件包：".$pkgname." (v".$pkgarr['pkgver']."-".$pkgarr['pkgrel'].")".PHP_EOL;
	$output=$output."仓库：".$pkgarr['repo'].PHP_EOL;
	$output=$output."简介：".$pkgarr['pkgdesc'].PHP_EOL;
	$output=$output."上游：".$pkgarr['url'].PHP_EOL;
	$output=$output."打包者：".$pkgarr['packager'].PHP_EOL;
	$output=$output.get_value($pkgarr,"depends","依赖","，");
	$output=$output.get_value($pkgarr,"optdepends","可选依赖",PHP_EOL);
	$output=$output.get_value($pkgarr,"conflicts","冲突","，");
	$output=$output.get_value($pkgarr,"provides","提供","，");
	$output=$output."体积：".round((intval($pkgarr['compressed_size'])/1024/1024),2)."MB (安装后  ".round((intval($pkgarr['installed_size'])/1024/1024),2)."MB)";
	send_msg($args,$output);
	return;
};

function get_value($pkgarr,$type,$type_txt,$splitstr){
	if (is_array($pkgarr[$type])){
		if ($pkgarr[$type]==array()) {
			$tmp=$type_txt."：无".PHP_EOL;
		} else {
			$tmp=$type_txt."：".implode($splitstr,$pkgarr[$type]).PHP_EOL;
		}
	} else {
		if (is_null($pkgarr[$type])) {
			$tmp=$type_txt."：无".PHP_EOL;
		} else {
			$tmp=$type_txt."：".$pkgarr[$type].PHP_EOL;
		};
	};
	return $tmp;
};

?> 
