<?php
header('Content-Type: application/json');
set_time_limit(0);
error_reporting(0);
include("function.php");
include("config.php");
$connectionInfo = array("Database" => $database_db_name, "UID" => $database_user, "PWD" => $database_password);
$connect_db = array(
	'1' => '$conn=mysql_connect($database_host,$database_user,$database_password) or die("connect Mysql database error!");
	mysql_select_db($database_db_name) or die("Select database error!");',

	'2' => '$conn=mysqli_connect($database_host,$database_user,$database_password,$database_db_name) or die("Error Mysqli Database is not connect!");',

	'3' => 'mssql_connect($database_host,$database_user,$database_password) or die("Mssql Database not Connect.. Please Check config");
	mssql_select_db ($database_db_name) or die("Mssql Select database error!");',

	'4' => '$conn=odbc_connect(\'Driver={SQL Server};Server=\' .$database_host. \';Database=\' . $database_db_name. \';\' ,$database_user, $database_password) or die(\'Error Odbc Mssql Database is not connect!\');',
	'5' => '$conn=sqlsrv_connect($database_host,$connectionInfo) or die("sqlsrv_connect to Mssql server Error ตรวจสอบการตั้งค่า Database");',
	'6' => '
	try{
		$conn = new PDO("sqlsrv:server=$database_host;Database=$database_db_name", $database_user, $database_password);
	}catch (PDOException $e) {
		die(\'Error PDO Sqlsrv Database is not connect!\');
	}
				',
);




if ($_POST["data"]) {
	$data_api = json_decode($_POST["data"], true);
	$ts = time();
	if (($ts - $data_api["timestamp"]) < 30) {
		$signature = md5($_POST["data"] . ":" . $tmweasy_api_key);
		if ($signature == $_POST["signature"]) {
			eval($connect_db[$database_type]);

			//---------------------------------อัพเดทฐานข้อมูลเครดิตรลูกค้่า เมื่อเติมสำเร็จ---------------------------------------------------------------------------------------------------
			$point = $data_api["amount"];
			$ref1 = $data_api["ref1"];
			$database_update = array(
				'1' => 'mysql_query("update $database_table set $database_point_field = $database_point_field + $point where $database_user_field = \'$ref1\' ");',
				'2' => 'mysqli_query($conn,"update $database_table set $database_point_field = $database_point_field + $point where $database_user_field = \'$ref1\' ");',
				'3' => 'mssql_query("update $database_table set $database_point_field = $database_point_field + $point where $database_user_field = \'$ref1\' ");',
				'4' => 'odbc_exec($conn,"update $database_table set $database_point_field = $database_point_field + $point where $database_user_field = \'$ref1\' ");',
				'5' => 'sqlsrv_query($conn,"update $database_table set $database_point_field = $database_point_field + $point where $database_user_field = \'$ref1\' ");',
				'6' => '$res = $conn->prepare("update $database_table set $database_point_field = $database_point_field + $point where $database_user_field = \'$ref1\' ");
						$res->execute();',
			);
			eval($database_update[$database_type]);

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
