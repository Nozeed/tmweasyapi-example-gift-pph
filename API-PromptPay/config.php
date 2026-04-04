<?php
$api_url = "https://tmwallet.thaighost.net/api_pph.php";
$tmweasy_user = ""; //Username ใช้ล็อกอินเว็บ https://www.tmweasyapi.com/login.php
$tmweasy_password = ""; //Password ใช้ล็อกอินเว็บ https://www.tmweasyapi.com/login.php
$tmweasy_api_key = ""; //Api Key https://www.tmweasyapi.com/memberseting.php?action=seting

$con_id = ""; //conid ที่ได้จากการเปิดใช้งาน Qr Promptpay Api บนเว็บ  tmweasy
$prommpay_no = "";  //เลข ID พร้อมเพย์ ใส่เฉพาะตัวเลขเช่น เบอร์โทร เลขบัตร ปชช 
$prommpay_type = "01"; //ประเพทพร้อมเพย์  01 = Mobile
$prommpay_name = ""; //ชื่อบัญชี

//--------------- การเชื่อม ฐานข้อมูล เพื่ออัพเดทเครดิตรให้ลูกค้า----------------
$database_host = "localhost";
$database_user = "root";
$database_password = "";
$database_db_name = "";
$database_type = "2"; //1 = mysql , 2 = mysqli ,3 = mssql (microsoft sql server) , 4 = Odbc for microsoft sql server , 5 = sqlsrv for microsoft sql server , 6 = pdo_sqlsrv  for microsoft sql server

$database_table = ""; //ตารางที่เต็มข้อมูลลูกค้า หรือ เก็บข้อมูลเครดิตร
$database_user_field = ""; //ฟิวที่ใช้ในการอ้างอิง user เช่น username userid
$database_point_field = ""; //ฟิวที่ใช้ในการเก็บค่า พ้อย เครดิตร ที่ต้องการให้อัพเดทหลังเต็มเสร็จ
