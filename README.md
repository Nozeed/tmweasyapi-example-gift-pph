# TMW Easy API - Example Gift + PromptPay (PPH)

ตัวอย่างการใช้งาน **TMW Easy API** สำหรับ

- **TrueWallet Gift** — เติมเงินบัตรของขวัญ TrueWallet
- **PromptPay (PPH)** — โอนเงินผ่าน PromptPay / ธนาคาร

โครงการนี้เป็นตัวอย่างโค้ด **PHP Native** (ไม่ใช้ Framework) ที่แสดงวิธีเรียกใช้งานทั้งสอง API อย่างง่ายและปลอดภัย

---

## คุณสมบัติ

- เรียกใช้งาน API ง่ายด้วยคลาสที่เขียนไว้ให้
- รองรับการตรวจสอบสถานะ (Check Status / Callback)
- ใช้ **PDO Prepared Statement** เพื่อความปลอดภัยจาก SQL Injection
- มีตัวอย่างโค้ดที่พร้อมรันในแต่ละโฟลเดอร์
- แยกโค้ดชัดเจนระหว่าง TrueWallet Gift และ PromptPay

## รูปแบบการทำงานของทั้ง 2 API

### 1. TrueWallet Gift API (โฟลเดอร์: `Api-TrueWallet-Gift`)

**วัตถุประสงค์**: ใช้สำหรับ **เติมเงินบัตรของขวัญ TrueWallet** ให้กับเบอร์โทรศัพท์ลูกค้า

#### Flow การทำงาน

```mermaid
flowchart TD
    A[ผู้ใช้ส่ง Gift Code / เติมเงิน] --> B[ระบบเรียก TMW Easy API - Topup Gift]
    B --> C{สำเร็จหรือไม่?}
    C -->|สำเร็จ| D[บันทึกสถานะสำเร็จในฐานข้อมูล]
    C -->|ไม่สำเร็จ| E[บันทึกสถานะล้มเหลว + ข้อความแจ้ง]
    D --> F[แจ้งผลให้ผู้ใช้ทราบ]
    E --> F
```
### 2. 2. PromptPay API (โฟลเดอร์: `API-PromptPay`)

**วัตถุประสงค์**: ใช้สำหรับ **โอนเงินผ่าน PromptPay (พร้อมเพย์)** โดยไม่ต้องกรอกเลขบัญชีธนาคาร

#### Flow การทำงาน

```mermaid
flowchart TD
    A[ผู้ใช้ขอโอนเงิน] --> B[ระบบสร้างรายการโอน + เรียก TMW Easy API]
    B --> C[API ส่งคำสั่งโอน PromptPay]
    C --> D{สถานะเริ่มต้น Pending}
    D --> E[บันทึก Transaction ใน DB]
    E --> F[รอ Webhook จาก TMW]
    
    F[TMW ส่ง Webhook กลับมา] --> G{ตรวจสอบ Signature}
    G -->|ถูกต้อง| H[อัปเดตสถานะเป็น Success/Failed]
    H --> I[บันทึก Log + แจ้งผู้ใช้]
    G -->|ผิด| J[ปฏิเสธ Webhook]
```
