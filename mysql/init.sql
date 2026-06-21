-- 1. คำสั่งสร้างตาราง users (ต้องปิดท้ายด้วยเซมิโคลอน ;)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    Tel VARCHAR(20) NOT NULL,
    course VARCHAR(255) NOT NULL,
    class_date DATE NOT NULL,
    pdpa_consent TINYINT(1) DEFAULT 0
);

-- 2. คำสั่งสร้างตาราง admins (ต้องปิดท้ายด้วยเซมิโคลอน ;)
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    reset_token VARCHAR(100) DEFAULT NULL,
    token_expiry DATETIME DEFAULT NULL
);

-- 3. คำสั่งใส่ข้อมูลแอดมินเริ่มต้น (ต้องปิดท้ายด้วยเซมิโคลอน ;)
INSERT INTO admins (username, password, email) 
VALUES ('admin', 'password', 'admin@econ.cmu.ac.th')
ON DUPLICATE KEY UPDATE username=username;