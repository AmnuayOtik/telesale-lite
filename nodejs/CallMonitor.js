const AsteriskManager = require('asterisk-manager');

// เชื่อมต่อกับ Asterisk AMI
const ami = new AsteriskManager(5038, '192.168.98.150', 'otikadmin2025', 'P@ssw0rd##', true);
ami.keepConnected();

// แจ้งเมื่อเชื่อมต่อสำเร็จ
ami.on('connect', () => {
  console.log('✅ Connected to Asterisk AMI');

  // ดึงรายการ channel ปัจจุบันทั้งหมด
  ami.action({ Action: 'CoreShowChannels' }, (err, res) => {
    if (err) {
      console.error('❌ CoreShowChannels error:', err);
    } else {
      console.log('📡 CoreShowChannels requested');
    }
  });
});

// ฟังทุก Event จาก Asterisk AMI
ami.on('managerevent', (event) => {
  // DEBUG: แสดงทุก event ที่เข้ามา
  console.log('📥 Event:', event);

  // ตรวจสอบเฉพาะสายที่โทรออกจาก extension 2888
  if (
    event.CallerIDNum === '2888' &&
    ['Newstate', 'DialBegin', 'Newchannel', 'CoreShowChannel', 'BridgeEnter'].includes(event.Event)
  ) {
    console.log('📞 พบการโทรออกโดยเบอร์ 2888');
    console.log('➡️ Event Type:', event.Event);
    console.log('➡️ Channel:', event.Channel);
    console.log('➡️ ปลายทาง:', event.ConnectedLineNum || event.Exten || event.Destination || 'ไม่ทราบ');
  }
});

// เมื่อหยุดโปรแกรม ให้ตัดการเชื่อมต่อ AMI
process.on('SIGINT', () => {
  console.log('\n⛔ Disconnecting from Asterisk AMI...');
  ami.disconnect();
  process.exit();
});
