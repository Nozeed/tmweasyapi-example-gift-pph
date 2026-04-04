<?php
function connect_api($url)
{
	$ch = curl_init($url);

	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; th; rv:1.9.2.12) Gecko/20101026 Firefox/3.6.12");
	curl_setopt($ch, CURLOPT_HEADER, 0);
	@curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	return curl_exec($ch);
	curl_close($ch);
	//return file_get_contents($url);
}

function alert_content($content, $type)
{
	$content_box = json_encode((string) $content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
	$script_alert = '<script type="text/javascript">alert(' . $content_box . ');</script>';
	echo $script_alert;
	$_SESSION["alert_content"] = "";
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
