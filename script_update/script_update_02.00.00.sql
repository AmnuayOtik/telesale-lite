-- สั่งให้เพิ่มฟิวใหม่ เป็น option สำหรับเลือกได้ว่าจะแสดงรายการที่ยังไม่ปิดหรือไม่ สถานะ <> closed
ALTER TABLE `users`
	ADD COLUMN `opt_not_closed` INT(1) NULL AFTER `user_type`;

-- สั่งอัปเดทรายการให้เป็น ปิด (ไม่เปิดฟังก์ชั่น)
UPDATE users set opt_not_closed = 0 WHERE opt_not_closed IS NULL;