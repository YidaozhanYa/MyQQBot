<?php
//desc 原神祈愿模拟
//usage <单抽/十连> <角色/角色二/武器/全角色/全武器/常驻>


function permission()
{
	global $allow_user;
	global $allow_group;
	global $deny_user;
	global $deny_group;
};

function msg_handler($args)
{
	if ($args['message_type'] == "group") {
		#if (do_cooldown('gi',300,$args)) {return;};
	};
	if ($args['command'] == "查询") {
		$giapi = gi_api("PrayInfo/GetMemberPrayDetail", $args, "");
		$retval = "";
		$retval = $retval . "一共抽了 " . $giapi['totalPrayTimes'] . " 发，";
		$retval = $retval . "角色池抽了 " . $giapi['rolePrayTimes'] . " 发，";
		$retval = $retval . "武器池抽了 " . $giapi['armPrayTimes'] . " 发，";
		$retval = $retval . "常驻池抽了 " . $giapi['permPrayTimes'] . " 发" . PHP_EOL;
		$retval = $retval . "角色池剩 " . $giapi['role90Surplus'] . " 抽保底，";
		$retval = $retval . "角色池剩 " . $giapi['role180Surplus'] . " 抽大保底，" . PHP_EOL;
		$retval = $retval . "武器池剩 " . $giapi['arm90Surplus'] . " 抽保底，";
		$retval = $retval . "武器池剩 " . $giapi['arm180Surplus'] . " 抽大保底，" . PHP_EOL;
		$retval = $retval . "常驻池剩 " . $giapi['perm90Surplus'] . " 抽保底，";
		$retval = $retval . "常驻池剩 " . $giapi['perm180Surplus'] . " 抽大保底，" . PHP_EOL;
		$retval = $retval . "抽到 " . $giapi['star4Count'] . " 个四星物品/角色，";
		$retval = $retval . "抽到 " . $giapi['star5Count'] . " 个五星物品/角色。";
		send_msg_topicture($args, $retval, "ys");
		return;
	} elseif ($args['command'] == "帮助") {
		send_msg($args,"参数一为模式，参数二为卡池：".PHP_EOL."<单抽/十连> <角色/角色二/武器/全角色/全武器/常驻>".PHP_EOL."或 <查询> 。".PHP_EOL."Powered By GenshinPray https://github.com/GardenHamster/GenshinPray");
		return;
	} else {
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
			$gacha = "武器池还剩 " . $giapi['arm90Surplus'] . " 抽保底";
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
	error_log($url);
	curl_setopt($curl, CURLOPT_URL, $url);
	$return_data = curl_exec($curl);
	curl_close($curl);
	error_log($return_data);
	return json_decode($return_data, true)['data'];
};
