<?php
header('Content-Type: application/json');
set_time_limit(0);
error_reporting(0);
include("function.php");
include("config.php");

// Direct PDO connection
try {
	$conn = new PDO("mysql:host=$database_host;dbname=$database_db_name;charset=utf8mb4", $database_user, $database_password);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
	die("Error PDO Database is not connect: " . $e->getMessage());
}

if ($_POST["data"]) {
	$data_api = json_decode($_POST["data"], true);
	$ts = time();
	if (($ts - $data_api["timestamp"]) < 30) {
		$signature = md5($_POST["data"] . ":" . $tmweasy_api_key);
		if ($signature == $_POST["signature"]) {
			//---------------------------------อัพเดทฐานข้อมูลเครดิตรลูกค้่า เมื่อเติมสำเร็จ---------------------------------------------------------------------------------------------------
			$point = $data_api["amount"];
			$ref1 = $data_api["ref1"];
			$stmt = $conn->prepare("update $database_table set $database_point_field = $database_point_field + :point where $database_user_field = :ref1");
			$stmt->bindParam(":point", $point);
			$stmt->bindParam(":ref1", $ref1);
			$stmt->execute();


			//-----------------------------------------------------------------------------------------------------------------
			$ch_date_cl = file_get_contents("dateclear.txt");
			if ($ch_date_cl != date("d")) {
				file_put_contents("paid_id.txt", "");
				file_put_contents("dateclear.txt", date("d"));
			}
			$lastdata = file_get_contents("paid_id.txt");
			$lastdata = json_decode($lastdata, true);

			$lastdata[$data_api['id_pay']] = time();

			$lastdata = json_encode($lastdata);
			file_put_contents("paid_id.txt", $lastdata);

			$callback = array("status" => 1, "msg" => "ok");
			die(json_encode($callback));
		} else {
			$callback = array("status" => 0, "msg" => "Data incorrect. Check API Key.");
			die(json_encode($callback));
		}
	} else {
		$callback = array("status" => 0, "msg" => "Data is not current.");
		die(json_encode($callback));
	}
} else {
	$callback = array("status" => 0, "msg" => "Data is empty.");
	die(json_encode($callback));
}
