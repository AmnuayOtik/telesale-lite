const AsteriskManager = require('asterisk-manager');
const mysql = require('mysql2');
const fs = require('fs');
const path = require('path');

// โหลด config จากไฟล์แยก
const config = JSON.parse(fs.readFileSync('./config/config.json', 'utf8'));

const amiConfig = config.asterisk;
const dbConfig = config.database;

// สร้างการเชื่อมต่อ AMI
const ami = new AsteriskManager(
  amiConfig.port,
  amiConfig.server,
  amiConfig.username,
  amiConfig.secret,
  true
);
ami.keepConnected();

// เชื่อมต่อฐานข้อมูล MySQL
const db = mysql.createConnection({
  host: dbConfig.host,
  user: dbConfig.user,
  password: dbConfig.password,
  database: dbConfig.database
});

// ฟังก์ชันเขียน log ลงไฟล์ตามวัน
function writeLog(message) {
  const now = new Date();
  const timestamp = now.toISOString().replace('T', ' ').substring(0, 19);
  const dateStr = now.toISOString().substring(0, 10); // YYYY-MM-DD

  const logDir = path.join(__dirname, 'logs');
  if (!fs.existsSync(logDir)) {
    fs.mkdirSync(logDir, { recursive: true });
  }

  const logFileName = path.join(logDir, `log-${dateStr}.txt`);
  const logMessage = `[${timestamp}] ${message}\n`;

  fs.appendFileSync(logFileName, logMessage);
  console.log(logMessage.trim());
}

// เมื่อเชื่อมต่อสำเร็จ
ami.on('connect', () => {
  writeLog('Connected to Asterisk AMI');
});

const processedCalls = new Set();

// ดักจับเหตุการณ์ BridgeEnter เมื่อมีการเชื่อมต่อสำเร็จ
ami.on('managerevent', (event) => {
  if (event.event === 'BridgeEnter' && event.channelstatedesc === 'Up') {
    const uniqueId = event.uniqueid;

    if (processedCalls.has(uniqueId)) return;
    processedCalls.add(uniqueId);

    const caller = event.calleridnum || event.srcexten || 'ไม่ทราบ';
    const callee = event.connectedlinenum || event.pbxdialnum || 'ไม่ทราบ';
    const channel = event.channel;

    if (caller !== 'ไม่ทราบ' && caller !== callee) {
      writeLog('พบการโทรออกจากเบอร์ต้นทางแล้วเชื่อมต่อสำเร็จ');
      writeLog(`เบอร์ต้นทาง: ${caller}`);
      writeLog(`เบอร์ปลายทาง: ${callee}`);
      writeLog(`Channel: ${channel}`);
      writeLog(`Uniqueid: ${uniqueId}`);
      writeLog('------------------------------------------------');

      const sql = 'UPDATE customers SET pbx_channel = ? WHERE src_exten = ? AND phone_number = ?';
      writeLog(`SQL: ${sql} | Values: [${channel}, ${caller}, ${callee}]`);

      db.execute(sql, [channel, caller, callee], (err, results) => {
        if (err) {
          writeLog(`Error updating database: ${err}`);
        } else {
          writeLog('Database updated successfully');
        }
      });
    }
  }
});

// เมื่อหยุดโปรแกรม
process.on('SIGINT', () => {
  writeLog('Disconnecting...');
  ami.disconnect();
  db.end();
  process.exit();
});
