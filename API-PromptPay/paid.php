<?php
error_reporting(0);
if ($_GET['id_pay']) {
	$readdata = file_get_contents("paid_id.txt");
	$decode = json_decode($readdata, true);
	if ($decode[$_GET['id_pay']]) {
		//unset($decode[$_GET['id_pay']]);
		//$lastdata=json_encode($decode);
		//file_put_contents("paid_id.txt",$lastdata);
		echo "ok";
	}
}
