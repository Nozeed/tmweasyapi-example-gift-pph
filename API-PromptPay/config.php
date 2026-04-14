<?php
$api_url = "https://www.tmweasy.com/api_pph.php"; //หรือสำรอง https://tmwallet.thaighost.net/api_pph.php
$tmweasy_user = ""; //Username ใช้ล็อกอินเว็บ https://www.tmweasyapi.com/login.php
$tmweasy_password = ""; //Password ใช้ล็อกอินเว็บ https://www.tmweasyapi.com/login.php
$tmweasy_api_key = ""; //api key ที่ได้จากการเปิดใช้งาน Qr Promptpay Api บนเว็บ  tmweasy

$con_id = ""; //conid ที่ได้จากการเปิดใช้งาน Qr Promptpay Api บนเว็บ  tmweasy
$prommpay_no = "";  //เลข ID พร้อมเพย์ ใส่เฉพาะตัวเลขเช่น เบอร์โทร เลขบัตร ปชช 
$prommpay_type = "01"; //ประเพทพร้อมเพย์  01 = Mobile, 02 = เลขบัตร ปชช, 03 = E-Wallet
$prommpay_name = ""; //ชื่อและนามสกุล

//--------------- การเชื่อม ฐานข้อมูล เพื่ออัพเดทเครดิตรให้ลูกค้า----------------
$database_host = "localhost";
$database_user = "root";
$database_password = "";
$database_db_name = "";

$database_table = ""; //ตารางที่เต็มข้อมูลลูกค้า หรือ เก็บข้อมูลเครดิตร
$database_user_field = ""; //ฟิวที่ใช้ในการอ้างอิง user เช่น username userid
$database_point_field = ""; //ฟิวที่ใช้ในการเก็บค่า พ้อย เครดิตร ที่ต้องการให้อัพเดทหลังเต็มเสร็จ
