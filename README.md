# TMW Easy API - Example Gift + PromptPay

ตัวอย่างการใช้งาน **TMW Easy API** สำหรับ  
- **TrueWallet Gift** (เติมเงินบัตรของขวัญ TrueWallet)  
- **PromptPay** (โอนเงินผ่าน PromptPay / ธนาคาร)

โครงการนี้เป็นตัวอย่างโค้ด PHP แสดงวิธีเรียกใช้งานทั้ง 2 API อย่างง่ายและสะดวก

## PDO
PDO prepared statement

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

### 2. PromptPay API (API-PromptPay)
วัตถุประสงค์: ใช้สำหรับโอนเงินผ่าน PromptPay (พร้อมเพย์) โดยไม่ต้องกรอกเลขบัญชี
