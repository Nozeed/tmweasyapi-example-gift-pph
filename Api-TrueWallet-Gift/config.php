<?php

$url_api = "https://tmwallet.thaighost.net/apiwallet.php"; // URL API TrueWallet gift

$tmweasy["user"] = ""; //Username ใช้ล็อกอินเว็บ https://www.tmweasyapi.com/login.php
$tmweasy["password"] = ""; //Password ใช้ล็อกอินเว็บ https://www.tmweasyapi.com/login.php
$truewallet["mobile"] = ""; //เบอร์ทรูวอเลท ตัวเลขเท่านั้น
$truewallet["name"] = ""; //ฃื่อบัญชีวอเลท


$database_set["truewallet"]["mul"] = 1; //ตัวคูณเครดิตรสำหรับทรูวอเลท และซองของขวีญ


$database_set["database_type"] = 2; //ตั้งค่าชนิด ฐานข้อมูล 0=ไม่เชื่อมฐานข้อมูล พัฒนาเพิ่มเอง | 1=mysql | 2=mysqli | 3=mssql | 4=odbc | 5=sqlsrv
$database_set["host"] = "localhost"; // Host ฐานข้อมูล
$database_set["user"] = "root"; // User ฐานข้อมูล
$database_set["password"] = ""; // Password ฐานข้อมูล
$database_set["db_name"] = ""; // ชื่อฐานข้อมูล
$database_set["user_table"] = ""; // ชื่อตารางผู้ใช้
$database_set["user_field"] = ""; // ชื่อฟิลด์ชื่อผู้ใช้
$database_set["point_field"] = ""; // ชื่อฟิลด์เครดิต
