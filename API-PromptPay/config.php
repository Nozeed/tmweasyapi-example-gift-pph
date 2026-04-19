<?php
$api_url = "https://tmwallet.thaighost.net/api_pph.php";
$tmweasy["user"] = ""; //Username ใช้ล็อกอินเว็บ https://www.tmweasyapi.com/login.php
$tmweasy["password"] = ""; //Password ใช้ล็อกอินเว็บ https://www.tmweasyapi.com/login.php
$tmweasy["api_key"] = ""; //api key ที่ได้จากการเปิดใช้งาน Qr Promptpay Api บนเว็บ  tmweasy

$promptpay["con_id"] = ""; //conid ที่ได้จากการเปิดใช้งาน Qr Promptpay Api บนเว็บ  tmweasy
$promptpay["no"] = "";  //เลข ID พร้อมเพย์ ใส่เฉพาะตัวเลขเช่น เบอร์โทร เลขบัตร ปชช 
$promptpay["type"] = "01"; //ประเพทพร้อมเพย์  01 = Mobile, 02 = เลขบัตร ปชช, 03 = E-Wallet
$promptpay["name"] = ""; //ชื่อและนามสกุล

//--------------- การเชื่อม ฐานข้อมูล เพื่ออัพเดทเครดิตรให้ลูกค้า----------------
$database_set["host"] = "localhost"; // Host ฐานข้อมูล
$database_set["user"] = "root"; // User ฐานข้อมูล
$database_set["password"] = ""; // Password ฐานข้อมูล
$database_set["db_name"] = ""; // ชื่อฐานข้อมูล

$database_set["table"] = ""; //ชื่อตารางผู้ใช้
$database_set["user_field"] = ""; //ชื่อฟิลด์ชื่อผู้ใช้
$database_set["point_field"] = ""; //ชื่อฟิลด์เครดิต
