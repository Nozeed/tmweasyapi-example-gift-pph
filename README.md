# TMW Easy API - Example Gift + PromptPay

ตัวอย่างการใช้งาน **TMW Easy API** สำหรับ  
- **TrueWallet Gift** (เติมเงินบัตรของขวัญ TrueWallet)  
- **PromptPay** (โอนเงินผ่าน PromptPay / ธนาคาร)

โครงการนี้เป็นตัวอย่างโค้ด PHP แสดงวิธีเรียกใช้งานทั้ง 2 API อย่างง่ายและสะดวก

## โครงสร้างโฟลเดอร์
├── Api-TrueWallet-Gift/     # โค้ดตัวอย่าง API TrueWallet Gift
├── API-PromptPay/          # โค้ดตัวอย่าง API PromptPay
└── README.md

## คุณสมบัติ

- เรียกใช้งาน API ง่ายด้วยคลาสที่เขียนไว้ให้
- รองรับการตรวจสอบสถานะ (Check Status)
- มีตัวอย่างการใช้งานที่ชัดเจน
- ใช้ PHP แบบ Native (ไม่ต้องติดตั้ง Framework)

## การติดตั้ง

### ตั้งค่า Config
แต่ละโฟลเดอร์จะมีไฟล์ config.php หรือตัวแปรสำหรับใส่ข้อมูลของคุณ

## รูปแบบการทำงานของ 2 API

### 1. TrueWallet Gift API (Api-TrueWallet-Gift)
วัตถุประสงค์: ใช้สำหรับเติมเงินบัตรของขวัญ TrueWallet (Gift) ให้กับเบอร์โทรศัพท์
ขั้นตอนการทำงานหลัก:

ส่งคำขอเติมเงิน (topup) พร้อมเบอร์โทร + มูลค่าบัตร
ระบบจะคืน transaction_id หรือ ref_id
ตรวจสอบสถานะธุรกรรม (check status) เพื่อยืนยันว่าสำเร็จหรือไม่

#### ฟังก์ชันหลัก:
```php
topupGift($phone, $amount, $note = '')
checkStatus($transaction_id)
```
### 2. PromptPay API (API-PromptPay)
วัตถุประสงค์: ใช้สำหรับโอนเงินผ่าน PromptPay (พร้อมเพย์) โดยไม่ต้องกรอกเลขบัญชี
ขั้นตอนการทำงานหลัก:

ส่งคำขอโอนเงินด้วยเบอร์โทรศัพท์ / บัตรประชาชน / e-Wallet
ระบบจะคืนผลลัพธ์ทันทีหรือแบบ Async
ตรวจสอบสถานะการโอน (check status)

#### ฟังก์ชันหลัก:
```php
transferPromptPay($target, $amount, $note = '')
checkTransferStatus($ref_id)
```
