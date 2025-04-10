************************************************
ระบบ Telesale System
พัฒนาโดย บริษัท โอติก เน็ตเวิร์ค จำกัด
ติดต่อ 02-538-4378,095-549-9819
************************************************

Requirement
    - PHP 7.4
    - MySQL Server
    - Codeigniter 3 (Framework)

ความสามารถของระบบ
    - บริหารจัดการลูกค้าได้ (Create , Update , Delete)
    - บริการจัดการ FollowUp ได้


วิธีการรัน PBXMonitor.js (NodeJS)

apt install sudo -y
sudo apt update
sudo apt upgrade

ติดตั้ง Node.js จาก NodeSource (เนื่องจาก Debian 12 อาจมีเวอร์ชันที่เก่ากว่าใน repository หลัก):
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -

ติดตั้ง Node.js และ npm:
sudo apt install -y nodejs

ตรวจสอบการติดตั้ง:
node -v
npm -v

หลังจากที่ติดตั้ง Node.js และ npm เสร็จแล้ว ให้ติดตั้ง PM2:
sudo npm install -g pm2

ตรวจสอบว่า PM2 ถูกติดตั้งเรียบร้อยแล้ว:
pm2 -v

ไปยังโฟลเดอร์ที่เก็บสคริปต์ Node.js ของคุณ (เช่น PBXMonitor.js):
cd /var/www/html/telesale-lite/nodejs/

รันสคริปต์ด้วย PM2:
pm2 start PBXMonitor.js --name pbx-monitor

ตั้งค่า PM2 ให้ทำงานอัตโนมัติเมื่อบูต
pm2 startup systemd

หลังจากที่ตั้งค่า startup เสร็จแล้ว ให้บันทึกการตั้งค่า PM2 ด้วยคำสั่ง:
pm2 save

*ตรวจสอบสถานะ PM2
pm2 status


ติดตั้ง Node.js เวอร์ชัน 20.15
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs

ืnodejs ที่ใช้ต้องไม่ต่ำกว่า v20.15.0


รีสตาร์ท PM2 ใหม่ทั้งหมด
รีสตาร์ท PM2 ใหม่ทั้งหมด (จะรีสตาร์ททุกแอปพลิเคชันที่รันอยู่บน PM2):
pm2 restart all

รีสตาร์ทแอปพลิเคชันเฉพาะ
หากคุณต้องการรีสตาร์ทแอปพลิเคชันที่ชื่อว่า your-app-name (หรือคุณสามารถใช้ ID ของแอปได้):
pm2 restart your-app-name

pm2 restart pbx-monitor

หรือถ้าคุณใช้ ID:

pm2 restart 0

รีสตาร์ท PM2 หลังจากการรีบูต
หากคุณต้องการให้ PM2 รีสตาร์ทแอปพลิเคชันอัตโนมัติหลังจากที่เครื่องถูกรีบูต:
pm2 startup

ตรวจสอบสถานะของ PM2
เพื่อตรวจสอบสถานะของแอปพลิเคชันที่รันอยู่:

pm2 status

เพื่อตรวจสอบบันทึก:
pm2 logs

