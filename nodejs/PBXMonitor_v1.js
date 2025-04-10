const AsteriskManager = require('asterisk-manager');
const fs = require('fs');

// โหลด config จากไฟล์ JSON
const config = JSON.parse(fs.readFileSync('./asterisk-config.json', 'utf8'));

// สร้างการเชื่อมต่อ AMI
const ami = new AsteriskManager(config.port, config.server, config.username, config.secret, true);
ami.keepConnected();

// เมื่อเชื่อมต่อสำเร็จ
ami.on('connect', () => {
  console.log('✅ Connected to Asterisk AMI');
});

// ฟัง event ที่ส่งเข้ามา
ami.on('managerevent', (event) => {
  // DEBUG: ลองเปิดดู event ทั้งหมดที่มาถ้าอยากเช็คว่าอะไรเกิดขึ้นบ้าง
  // console.log('📥 Full Event:', event);

  // ตรวจสอบว่าเป็นการโทรออกจากเบอร์ 2888 และอยู่ในสถานะ "Up"
  if (
    event.event === 'Newstate' &&
    event.channelstatedesc === 'Up' &&
    (event.calleridnum === '2888' || event.srcexten === '2888')
  ) {
    console.log('พบการโทรออกจากเบอร์ 2888 แล้วเชื่อมต่อสำเร็จ');
    console.log('- ปลายทาง:', event.connectedlinenum || event.pbxdialnum || 'ไม่ทราบ');
    console.log('- Channel:', event.channel);
    console.log('- Uniqueid:', event.uniqueid);
  }
});

// ตัดการเชื่อมต่อเมื่อ process ถูก kill
process.on('SIGINT', () => {
  console.log('⛔ Disconnecting from AMI...');
  ami.disconnect();
  process.exit();
});
