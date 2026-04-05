<?php
// https://www.tmweasyapi.com/pph_qr.php
ob_start();
session_start();
set_time_limit(0);
error_reporting(0);
include("function.php");

$config = file_exists("config.php") ? include("config.php") : [];

$database_check = array(
	"1" => "mysql_connect",
	"2" => "mysqli_connect",
	"3" => "mssql_connect",
	"4" => "odbc_connect",
	"5" => "sqlsrv_connect"
);

// Direct PDO connection
try {
	$conn = new PDO("mysql:host=$database_host;dbname=$database_db_name;charset=utf8mb4", $database_user, $database_password);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
	die("Error PDO Database is not connect: " . $e->getMessage());
}


if ($_GET["action"] == "cancel") {
	$connect_api = connect_api($api_url . "?username=$tmweasy_user&password=$tmweasy_password&con_id=$con_id&method=cancel&id_pay=" . $_SESSION["id_pay"]);
	$_SESSION["id_pay"] = "";
	header("location:index.php");
	die();
}
if ($_GET["action"] == "exit") {
	$_SESSION["id_pay"] = "";
	header("location:index.php");
	die();
}


if ($_POST["amount"]) {
	$connect_api = connect_api($api_url . "?username=$tmweasy_user&password=$tmweasy_password&amount=" . $_POST["amount"] . "&ref1=" . $_POST["ref1"] . "&con_id=$con_id&ip=" . my_ip() . "&method=create_pay");
	$connect_api = json_decode($connect_api, true);
	if ($connect_api["status"] != "1") {

		$_SESSION["alert_content"] = "Error : " . $connect_api["msg"];
		$_SESSION["alert_type"] = "alert-danger";
		header("location:index.php");
		die();
	} else {
		$_SESSION["id_pay"] = $connect_api["id_pay"];
		header("location:index.php");
		die();
	}
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>PromptPay API</title>
	<script>
		function pad(n, width, fill) {
			n = String(n);
			return n.length >= width ? n : new Array(width - n.length + 1).join(fill) + n;
		}

		function timeDisplay(timeS) {
			var min = pad(Math.floor(timeS / 60), 2, 0);
			var sec = pad(Math.abs((Math.floor(timeS / 60) * 60) - timeS), 2, 0);
			var target = document.getElementById("time_count_down");
			if (!target) {
				return;
			}
			if (timeS <= 0) {
				target.innerHTML = "หมดเวลาโอนเงิน <a href='?action=cancel'>ยกเลิกและเริ่มใหม่</a>";
				return;
			}
			target.textContent = min + " : " + sec;
		}

		function startCountdown(seconds) {
			var current = seconds;
			timeDisplay(current);
			var timer = setInterval(function() {
				current -= 1;
				timeDisplay(current);
				if (current <= 0) {
					clearInterval(timer);
				}
			}, 1000);
		}

		function startPaymentCheck(url) {
			setInterval(function() {
				fetch(url, {
						cache: "no-store"
					})
					.then(function(response) {
						return response.text();
					})
					.then(function(data) {
						if (data.trim() === "ok") {
							window.location = "?action=success";
						}
					});
			}, 3000);
		}
	</script>
</head>

<body>
	<h1>PromptPay API</h1>
	<p>หลักการทำงานของ API</p>
	<p>- Ref1 / Username หรือ ID ตามที่ตั้งค่าใน Config</p>
	<p>- สร้าง QR ผ่าน API พร้อมเพย์ https://tmwallet.thaighost.net/apiwallet.php</p>
	<p>- แสดง QR ให้สแกน</p>
	<p>- รอ callback</p>
	<p>- ตรวจสอบสถานะ</p>
	<p>- เติมเงินเมื่อสำเร็จ</p>
	<?php
	if ($_GET["action"] == "success") {
	?>
		<h2>ทำรายการสำเร็จแล้ว</h2>
		<p>ตรวจสอบเครดิตของคุณได้เลย หากมีปัญหาให้ติดต่อผู้ดูแลระบบ</p>
		<p><a href="?action=exit">เริ่มรายการใหม่</a></p>
		<?php
	} else {
		if ($_SESSION["id_pay"]) {
			$connect_api = connect_api($api_url . "?username=$tmweasy_user&password=$tmweasy_password&con_id=$con_id&id_pay=" . $_SESSION["id_pay"] . "&type=$prommpay_type&promptpay_id=$prommpay_no&method=detail_pay");
			$connect_api = json_decode($connect_api, true);
			if ($connect_api["status"] != "1") {
				$_SESSION["id_pay"] = "";
				$_SESSION["alert_content"] = "Error : " . $connect_api["msg"];
				$_SESSION["alert_type"] = "alert-danger";
				header("location:index.php");
				die();
			}
			$prompay_type = array("01" => "เบอร์มือถือ", "02" => "เลขบัตร ปชช", "03" => "E-Wallet");
			$qr_url = "data:image/png;base64," . $connect_api["qr_image_base64"];
		?>
			<h2>รายละเอียดรายการชำระ</h2>
			<p><strong>Ref1:</strong> <?= $connect_api["ref1"] ?></p>
			<p><strong>เลขพร้อมเพย์:</strong> <?= $prommpay_no ?></p>
			<p><strong>ประเภท:</strong> <?= $prompay_type[$prommpay_type] ?></p>
			<p><strong>ชื่อบัญชี:</strong> <?= $prommpay_name ?></p>
			<p><strong>ยอดที่ต้องโอน:</strong> <?= number_format($connect_api["amount_check"] / 100, 2) ?> บาท</p>
			<p><strong>หมายเหตุ:</strong> โอนให้ตรงยอดที่ระบบแสดงเท่านั้น</p>
			<p><img src="<?= $qr_url ?>" alt="PromptPay QR" style="max-width:320px;width:100%;"></p>
			<p>เวลาคงเหลือ: <span id="time_count_down">--</span></p>
			<p>
				<a href="?action=cancel">ยกเลิกรายการ</a>
				|
				<a href="?action=exit">ออกจากหน้านี้</a>
			</p>
			<script>
				startCountdown(<?= (int) $connect_api["time_out"] ?>);
				startPaymentCheck("paid.php?id_pay=<?= $_SESSION['id_pay'] ?>&one=" + Math.floor(Math.random() * 1000001));
			</script>
		<?php
		} else {
			$connect_api = connect_api($api_url . "?username=$tmweasy_user&password=$tmweasy_password&con_id=$con_id");
			$connect_api = json_decode($connect_api, true);
			if ($connect_api["status"] != "1") {
				$_SESSION["alert_content"] = "Error : " . $connect_api["msg"];
				$_SESSION["alert_type"] = "alert-danger";
			}
		?>
			<h2>สร้างรายการใหม่</h2>
			<form method="POST">
				<p>
					<label for="ref1">Ref1 / User / ID</label><br>
					<input id="ref1" name="ref1" type="text" value="<?= $_GET["ref1"] ?>" required>
				</p>
				<p>
					<label for="amount">จำนวนเงิน</label><br>
					<input id="amount" name="amount" type="number" min="1" required>
				</p>
				<p><button type="submit">สร้าง QR Payment</button></p>
			</form>
	<?php
		}
	}

	if ($_SESSION["alert_content"]) {
		alert_content($_SESSION["alert_content"], $_SESSION["alert_type"]);
	}
	?>
</body>

</html>
