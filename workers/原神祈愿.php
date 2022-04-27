<?php
//desc 原神祈愿模拟
//usage 详见 ::原神祈愿 帮助


function permission()
{
	global $allow_user;
	global $allow_group;
	global $deny_user;
	global $deny_group;
};

function msg_handler($args)
{
	if ($args['command'] == "查询") {
		$message_id=send_msg($args,"⏰ 正在查询祈愿数据 ...");
		$retval = "当前可用的卡池：".PHP_EOL;
		$retval = $retval . "角色卡池：白鹭之庭（白鹭霜华·神里绫华 冰 / 雷泽 罗莎莉亚 早柚）".PHP_EOL;
		$retval = $retval . "角色二卡池：杯装之市（风色诗人·温迪 风 / 砂糖 香菱 云堇）".PHP_EOL;
		$retval = $retval . "武器卡池：神铸赋形（雾切之回光 无工之剑 / 西风剑 西风长枪 西风秘典 西风猎弓）".PHP_EOL.PHP_EOL;
		$giapi = gi_api("PrayInfo/GetMemberPrayDetail", $args, "");
		$retval = $retval . $args['sender']['nickname']."的祈愿数据：".PHP_EOL;
		$retval = $retval . "一共抽了 " . checknull($giapi['totalPrayTimes']) . " 发，";
		$retval = $retval . "角色池抽了 " . checknull($giapi['rolePrayTimes']) . " 发，";
		$retval = $retval . "武器池抽了 " . checknull($giapi['armPrayTimes']) . " 发，";
		$retval = $retval . "常驻池抽了 " . checknull($giapi['permPrayTimes']) . " 发。" . PHP_EOL;
		$retval = $retval . "角色池剩 " . checknull($giapi['role90Surplus']) . " 抽保底，";
		$retval = $retval . "角色池剩 " . checknull($giapi['role180Surplus']) . " 抽大保底，" . PHP_EOL;
		$retval = $retval . "武器池剩 " . checknull($giapi['arm90Surplus']) . " 抽保底，";
		$retval = $retval . "武器池剩 " . checknull($giapi['arm180Surplus']) . " 抽大保底，" . PHP_EOL;
		$retval = $retval . "常驻池剩 " . checknull($giapi['perm90Surplus']) . " 抽保底，";
		$retval = $retval . "常驻池剩 " . checknull($giapi['perm180Surplus']) . " 抽大保底，" . PHP_EOL;
		$retval = $retval . "抽到 " . checknull($giapi['star4Count']) . " 个四星物品/角色，";
		$retval = $retval . "抽到 " . checknull($giapi['star5Count']) . " 个五星物品/角色。".PHP_EOL;
		$giapi = gi_api("PrayInfo/GetMemberAssign", $args, "");
		$retval = $retval . "当前定轨武器为 " . checknull($giapi['goodsName'])." (".checknull($giapi['goodsSubType']).")，命定值为 ".checknull($giapi['assignValue'])." 。".PHP_EOL.PHP_EOL;
		$giapi = gi_api("PrayInfo/GetMemberPrayRecords", $args, "");
		$retval = $retval . "抽到的五星：";
		foreach($giapi['star5']['all'] as $item){
			$retval = $retval . $item['goodsName']." (".$item['goodsSubType'].")  ";
		};
		$retval = $retval .PHP_EOL. "抽到的四星：";
		$counter=0;
		foreach($giapi['star4']['all'] as $item){
			$retval = $retval . $item['goodsName']." (".$item['goodsSubType'].")  ";
			$counter+=1;
			if ($counter==5){
				$counter=0;$retval = $retval . PHP_EOL;
			};
		};
		$giapi = gi_api("PrayInfo/GetLuckRanking", $args, "");
		$retval = $retval .PHP_EOL. "所有用户的祈愿数据：".PHP_EOL;
		$retval=$retval."1st：".$giapi['star5Ranking'][0]['memberName']." (".$giapi['star5Ranking'][0]['memberCode'].") ，共抽了 ".$giapi['star5Ranking'][0]['totalPrayTimes']." 发，其中五星 ".$giapi['star5Ranking'][0]['count']." 个，出货率 ".$giapi['star5Ranking'][0]['rate']."%。".PHP_EOL;
		$retval=$retval."2nd：".$giapi['star5Ranking'][1]['memberName']." (".$giapi['star5Ranking'][1]['memberCode'].") ，共抽了 ".$giapi['star5Ranking'][1]['totalPrayTimes']." 发，其中五星 ".$giapi['star5Ranking'][1]['count']." 个，出货率 ".$giapi['star5Ranking'][1]['rate']."%。".PHP_EOL;
		if(is_null($giapi['star5Ranking'][2])==false){
			$retval=$retval."3rd：".$giapi['star5Ranking'][2]['memberName']." (".$giapi['star5Ranking'][2]['memberCode'].") ，共抽了 ".$giapi['star5Ranking'][2]['totalPrayTimes']." 发，其中五星 ".$giapi['star5Ranking'][2]['count']." 个，出货率 ".$giapi['star5Ranking'][2]['rate']."%。";
		};
		delete_msg($message_id);
		send_msg_topicture($args, $retval, "ys");
		return;
	}  elseif (strpos($args['command'],"定轨") !==false) {
		if (strpos($args['command']," ")==false){
			send_msg($args,"参数错误，详见 ::原神祈愿 帮助");
			return;
		}
		$gacha_args = explode(" ", $args['command'], 2);
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("authorzation:" . GI_AUTH));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 120);
		curl_setopt($curl, CURLOPT_TIMEOUT, 120);
		$url = GI_API . "/PrayInfo/SetMemberAssign?memberCode=" . $args['user_id'] . "&memberName=" . $args['sender']['nickname'] ."&goodsName=".$gacha_args[1];
		curl_setopt($curl, CURLOPT_URL, $url);
		$return_data = curl_exec($curl);
		curl_close($curl);
		if(json_decode($return_data, true)['code']==0){
			send_msg($args,"☑️ 定轨 ".$gacha_args[1]." 成功。");
		} else {
			send_msg($args,"❌ 定轨失败，这个武器不是当前 UP 武器或不存在。");
		};
		return;
	} elseif ($args['command'] == "帮助") {
		send_msg($args,"参数一为模式，参数二为卡池：".PHP_EOL."<单抽/十连> <角色/角色二/武器/全角色/全武器/常驻> 以抽卡，".PHP_EOL."<查询> 以查询当前卡池，自己数据和欧气排行，".PHP_EOL."<定轨> <武器名> 来定轨武器池。".PHP_EOL."Powered By GenshinPray https://github.com/GardenHamster/GenshinPray");
		return;
	} else {
		if (strpos($args['command']," ")==false){
			send_msg($args,"参数错误，详见 ::原神祈愿 帮助");
			return;
		}
		if ($args['message_type'] == "group") {
			if (do_cooldown('gi',60,$args)) {return;};
		};
		// 正式
		$gacha_args = explode(" ", $args['command'], 2);
		if ($gacha_args[0] == "十连") {
			if ($gacha_args[1] == "角色") {
				$giapi = gi_api("RolePray/PrayTen", $args, "");
			} elseif ($gacha_args[1] == "角色二") {
				$giapi = gi_api("RolePray/PrayTen", $args, "&pondIndex=1");
			} elseif ($gacha_args[1] == "武器") {
				$giapi = gi_api("ArmPray/PrayTen", $args, "");
			} elseif ($gacha_args[1] == "常驻") {
				$giapi = gi_api("PermPray/PrayTen", $args, "");
			} elseif ($gacha_args[1] == "全角色") {
				$giapi = gi_api("FullRolePray/PrayTen", $args, "");
			} elseif ($gacha_args[1] == "全武器") {
				$giapi = gi_api("FullArmPray/PrayTen", $args, "");
			};
		} else {
			if ($gacha_args[1] == "角色") {
				$giapi = gi_api("RolePray/PrayOne", $args, "");
			} elseif ($gacha_args[1] == "角色二") {
				$giapi = gi_api("RolePray/PrayOne", $args, "&pondIndex=1");
			} elseif ($gacha_args[1] == "武器") {
				$giapi = gi_api("ArmPray/PrayOne", $args, "");
			} elseif ($gacha_args[1] == "常驻") {
				$giapi = gi_api("PermPray/PrayOne", $args, "");
			} elseif ($gacha_args[1] == "全角色") {
				$giapi = gi_api("FullRolePray/PrayOne", $args, "");
			} elseif ($gacha_args[1] == "全武器") {
				$giapi = gi_api("FullArmPray/PrayOne", $args, "");
			};
		};
		if ($gacha_args[1] == "角色") {
			$gacha = "角色池还剩 " . $giapi['role90Surplus'] . " 抽保底";
		} elseif ($gacha_args[1] == "角色二") {
			$gacha = "角色池还剩 " . $giapi['role90Surplus'] . " 抽保底";
		} elseif ($gacha_args[1] == "武器") {
			$gacha = "武器池还剩 " . $giapi['arm90Surplus'] . " 抽保底，".PHP_EOL."命定值为 ".$giapi['armAssignValue'];
		} elseif ($gacha_args[1] == "常驻") {
			$gacha = "常驻池还剩 " . $giapi['perm90Surplus'] . " 抽保底";
		} elseif ($gacha_args[1] == "全角色") {
			$gacha = "全角色特殊池还剩 " . $giapi['fullRole90Surplus'] . " 抽保底";
		} elseif ($gacha_args[1] == "全武器") {
			$gacha = "全武器特殊池还剩 " . $giapi['fullArm90Surplus'] . " 抽保底";
		};
		$gacha = $gacha . "，" . PHP_EOL . "今日还剩 " . $giapi["apiDailyCallSurplus"] . "次。";
		send_msg($args, "[CQ:image,file=" . $giapi['imgHttpUrl'] . "]");
		send_msg($args, $gacha, "ys");
		return;
	};
};

function gi_api($api_path, $args, $extra)
{
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_HEADER, 0);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array("authorzation:" . GI_AUTH));
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 0);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 120);
	curl_setopt($curl, CURLOPT_TIMEOUT, 120);
	$url = GI_API . "/" . $api_path . "?memberCode=" . $args['user_id'] . "&memberName=" . $args['sender']['nickname'] . $extra;
	curl_setopt($curl, CURLOPT_URL, $url);
	$return_data = curl_exec($curl);
	curl_close($curl);
	return json_decode($return_data, true)['data'];
};

function checknull($item){
	if (is_null($item)) {
		return "(无数据)";
	} else {
		return $item;
	}
};