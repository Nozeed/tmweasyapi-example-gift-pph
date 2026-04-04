<?php
include("function.php");
if ($_GET['url']) {
	header('Content-Type: image/png');
	echo connect_api(urldecode($_GET['url']));
}
