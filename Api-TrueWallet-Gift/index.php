<?php
// https://www.tmweasyapi.com/faq.php
ob_start();
session_start();
date_default_timezone_set("Asia/Bangkok");
error_reporting(0);

function connect_api($url)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; th; rv:1.9.2.12) Gecko/20101026 Firefox/3.6.12");
    curl_setopt($ch, CURLOPT_HEADER, 0);
    @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $respone = curl_exec($ch);
    curl_close($ch);
    return $respone;
}

function alert_content($content, $type)
{
    $content_box = json_encode((string) $content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $script_alert = '<script type="text/javascript">alert(' . $content_box . ');</script>';
    echo $script_alert;
    $_SESSION['alert_content'] = "";
}

function my_ip()
{
    if ($_SERVER['HTTP_CLIENT_IP']) {
        $IP = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (preg_match("[0-9]", $_SERVER["HTTP_X_FORWARDED_FOR"])) {
        $IP = $_SERVER["HTTP_X_FORWARDED_FOR"];
    } else {
        $IP = $_SERVER["REMOTE_ADDR"];
    }
    return $IP;
}

function tf($content)
{
    if ($content == "true") {
        return "true";
    } else {
        return "false";
    }
}

$config = @include("config.php");
$database_host = $database_set['host'];
$database_user = $database_set['user'];
$database_password = $database_set['password'];
$database_db_name = $database_set['db_name'];
$database_table = $database_set['user_table'];
$database_user_field = $database_set['user_field'];
$database_point_field = $database_set['point_field'];

// Direct PDO connection
try {
    $conn = new PDO("mysql:host=$database_host;dbname=$database_db_name;charset=utf8mb4", $database_user, $database_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error PDO Database is not connect! ตรวจสอบการตั้งค่า Database: " . $e->getMessage());
}

$tran_id = false;
if (@$_POST['transactionid']) {
    switch ($_POST['transactionid']) {
        case "truegift":
            if (strpos($_POST["gifturl"], "ttp")) {
                $tran_id = trim($_POST["gifturl"]);
            } else {
                $_SESSION["alert_content"] = "Error : การกรอก Url ไม่ถูกต้อง";
                $_SESSION["alert_type"] = "alert-danger";
            }
            break;
    }

    if ($tran_id) {
        $send_api = connect_api($url_api . "?username=" . $tmweasy['user'] . "&password=" . $tmweasy['password'] . "&tmemail=" . $truewallet["mobile"] . "&transactionid=" . $tran_id . "&clientip=" . urlencode(my_ip()) . "&ref1=" . $_POST['ref1'] . "&action=yes&json=1");
        $api_respone = json_decode($send_api, true);

        if ($api_respone['Status'] == "check_success") {
            $money_total = $api_respone['Amount'];
            if ($api_respone['Type'] == "truemoney") {
                $point = $database_set['truemoney'][$money_total];
            } else {
                $point = $money_total;
            }

            $ref1 = $_POST['ref1'];
            // Direct PDO prepared statement
            $stmt = $conn->prepare("update $database_table set $database_point_field = $database_point_field + :point where $database_user_field = :ref1");
            $stmt->bindParam(":point", $point);
            $stmt->bindParam(":ref1", $ref1);
            $stmt->execute();

            $_SESSION["alert_content"] = "จำนวนเงิน คือ " . number_format($money_total) . " บาท ได้รับ  " . number_format($point) . " เครดิตร \n ขอบคุณที่ใช้บริการครับ   [ ปิดหน้านี้ได้เลย! ]";
            $_SESSION["alert_type"] = "alert-success";
            header("location:index.php");
            die();
        } else {
            $_SESSION["alert_content"] = $api_respone['Msg'];
            $_SESSION["alert_type"] = "alert-danger";
        }
    }
} else {
    $check_api = connect_api($url_api . "?username=" . $tmweasy['user'] . "&password=" . $tmweasy['password'] . "&json=1");
    $check_api = json_decode($check_api, true);
    if ($check_api['Status'] != "ready") {
        $_SESSION["alert_content"] = "Error : " . $check_api['Msg'];
        $_SESSION["alert_type"] = "alert-danger";
    }
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrueWallet API</title>
</head>

<body>
    <h1>TrueWallet Gift API</h1>
    <p>หลักการทำงานของ API</p>
    <p>- Ref1 / Username หรือ ID ตามที่ตั้งค่าใน Config</p>
    <p>- รับ URL ซองของขวัญและส่งเข้า API TrueWallet https://tmwallet.thaighost.net/apiwallet.php</p>
    <p>- ตรวจสอบข้อมูลรายการจาก API</p>
    <p>- รอผลการทำรายการ</p>
    <p>- ตรวจสอบสถานะ</p>
    <p>- เติมเงินเมื่อสำเร็จ</p>
    <h2>ตรวจสอบเติมเงิน</h2>
    <form method="post">
        <p>
            <label for="ref1">Ref1 / User / ID</label><br>
            <input type="text" name="ref1" id="ref1" value="<?= @$_GET["ref1"] ?>" required>
        </p>
        <p>
            <label for="gifturl">URL ซองของขวัญ</label><br>
            <input type="url" name="gifturl" id="gifturl" placeholder="https://gift.truemoney.com/..." required>
        </p>
        <input type="hidden" name="transactionid" value="truegift">
        <p><button type="submit">ตรวจสอบเติมเงิน</button></p>
    </form>
    <?php
    if ($_SESSION['alert_content']) {
        alert_content($_SESSION['alert_content'], $_SESSION['alert_type']);
    }
    ?>
</body>

</html>
