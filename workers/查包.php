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
	$message_id=send_msg($args,"正在查询软件包 ".$pkgname." ...");
	$url="https://archlinux.org/packages/search/json/?name=".$pkgname;
    $pkgarr=json_decode(get_data($url,0,0),true);
    if ($pkgarr['results']==array()) {
    	//ArchLinuxCN
    	$pkgbuild=get_data("https://raw.githubusercontent.com/archlinuxcn/repo/master/archlinuxcn/".$pkgname."/PKGBUILD",0,0);
		if ($pkgbuild!=="404: Not Found"){
			$pkgbuild=str_replace("' '","，' '",$pkgbuild);
			$pkgbuild=str_replace("\" \"","，' '",$pkgbuild);
			$pkgbuild=$pkgbuild.PHP_EOL.PHP_EOL.'echo ${pkgver};echo ${pkgrel};echo ${pkgdesc};echo ${depends[@]};echo ${optdepends[@]};echo ${url};';
			$pkgbuild=$pkgbuild.PHP_EOL.PHP_EOL.'echo ${provides[@]};echo ${conflicts[@]};echo ${replaces[@]};echo ${license};'; //添加所需输出变量
			file_put_contents(getcwd()."/pkgbuild.sh",$pkgbuild); //生成pkgbuild脚本
			exec("bash ".getcwd()."/pkgbuild.sh",$pkgarr); //调用bash运行pkgbuild
			$output="第三方仓库软件包：".$pkgname." (v".$pkgarr[0]."-".$pkgarr[1].")".PHP_EOL;
			$output=$output."仓库：archlinuxcn".PHP_EOL;
			$output=$output."简介：".$pkgarr[2].PHP_EOL;
			$output=$output."上游：".$pkgarr[5].PHP_EOL;
			if ($pkgarr[9]!==""){$output=$output."开源许可：".$pkgarr[9].PHP_EOL;}
			if ($pkgarr[3]!==""){$output=$output."依赖：".$pkgarr[3].PHP_EOL;}
			if ($pkgarr[4]!==""){$output=$output."可选依赖：".$pkgarr[4].PHP_EOL;}
			if ($pkgarr[7]!==""){$output=$output."冲突：".$pkgarr[7].PHP_EOL;}
			if ($pkgarr[6]!==""){$output=$output."提供：".$pkgarr[6].PHP_EOL;}
			if ($pkgarr[8]!==""){$output=$output."覆盖：".$pkgarr[8].PHP_EOL;}
			send_msg($args,trim($output));
			delete_msg($message_id);
			return;
		};
		$third_repo="AUR";
		//Clansty
		$pkglist=get_data("https://raw.githubusercontent.com/Clansty/arch-build/next/pkglist.yaml",0,0);
		if (strpos($pkglist,"- ".$pkgname)!==false) {$third_repo=$third_repo.", Clansty";};
		//aya
		$pkglist=get_data("https://raw.githubusercontent.com/Brx86/repo/master/.github/workflows/build.yml",0,0);
		if (strpos($pkglist,"- ".$pkgname)!==false) {$third_repo=$third_repo.", aya";};
    	//AUR
    	$url="https://aur.archlinux.org/rpc/?v=5&type=info&arg=".$pkgname;
    	$pkgarr=json_decode(get_data($url,0,0),true);
   	    if ($pkgarr['resultcount']==0) {
			delete_msg($message_id);
			$message_id2=send_msg($args,"正在模糊搜索软件包 ".$pkgname." ... (仅支持官方仓库和 AUR)");
			$url="https://archlinux.org/packages/search/json/?q=".$pkgname;
			$pkgarr1=json_decode(get_data($url,0,0),true);
			error_log(json_encode($pkgarr1));
			$output="软件仓库搜索结果如下：".PHP_EOL;
			foreach($pkgarr1['results'] as $pkgarr) {
			$output=$output."- ".$pkgarr['pkgname']." (v".$pkgarr['pkgver']."-".$pkgarr['pkgrel'].") 在 ".$pkgarr['repo'].PHP_EOL;
			$output=$output."简介：".$pkgarr['pkgdesc'].PHP_EOL;
			};
			$url="https://aur.archlinux.org/rpc/?v=5&type=search&arg=".$pkgname;
			$pkgarr1=json_decode(get_data($url,0,0),true);
			error_log(json_encode($pkgarr1));
			$output=$output."AUR 搜索结果如下：".PHP_EOL;
			foreach($pkgarr1['results'] as $pkgarr) {
			$output=$output."- ".$pkgarr['Name']." (v".$pkgarr['Version'].")".PHP_EOL;
			$output=$output."简介：".$pkgarr['Description'].PHP_EOL;
			};
			if ($output!=="软件仓库搜索结果如下：".PHP_EOL."AUR 搜索结果如下：".PHP_EOL){
				send_msg_topicture($args,$output,'kde');
			} else {
				send_msg($args,$pkgname." 软件包不存在。");
			};
			delete_msg($message_id2);
	    	return;
		};
		$pkgarr=$pkgarr['results'][0];
		if ($third_repo=="AUR"){
			$output="AUR 软件包：".$pkgname." (v".$pkgarr['Version'].")".PHP_EOL;
		} else {
			$output="第三方软件包：".$pkgname." (v".$pkgarr['Version'].")".PHP_EOL;
			$output=$output."仓库：".$third_repo.PHP_EOL;
		};
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
		send_msg($args,trim($output));
		delete_msg($message_id);
		return;
	};
    error_log(json_encode($pkgarr));
    $pkgarr=$pkgarr['results'][0];
	$output="官方仓库软件包：".$pkgname." (v".$pkgarr['pkgver']."-".$pkgarr['pkgrel'].")".PHP_EOL;
	$output=$output."仓库：".$pkgarr['repo'].PHP_EOL;
	$output=$output."简介：".$pkgarr['pkgdesc'].PHP_EOL;
	$output=$output."上游：".$pkgarr['url'].PHP_EOL;
	$output=$output."打包者：".$pkgarr['packager'].PHP_EOL;
	$output=$output.get_value($pkgarr,"depends","依赖","，");
	$output=$output.get_value($pkgarr,"optdepends","可选依赖",PHP_EOL);
	$output=$output.get_value($pkgarr,"conflicts","冲突","，");
	$output=$output.get_value($pkgarr,"provides","提供","，");
	$output=$output."体积：".round((intval($pkgarr['compressed_size'])/1024/1024),2)."MB (安装后  ".round((intval($pkgarr['installed_size'])/1024/1024),2)."MB)";
	send_msg($args,trim($output));
	delete_msg($message_id);
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
