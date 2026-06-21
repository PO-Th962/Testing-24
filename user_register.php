<?php
include 'db.php';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if ($username != '' && $email != '' && $password != '') {
        if ($password === $confirm_password) {
            try {
                // ตรวจสอบว่ามีชื่อผู้ใช้หรืออีเมลนี้ซ้ำไหม
                $checkStmt = $conn->prepare("SELECT COUNT(*) FROM user_accounts WHERE username = ? OR email = ?");
                $checkStmt->execute([$username, $email]);
                if ($checkStmt->fetchColumn() > 0) {
                    $message = "<div class='message' style='background:#f8d7da; color:#721c24;'>Username หรือ Email นี้ถูกใช้ไปแล้ว!</div>";
                } else {
                    // บันทึกบัญชี User ทั่วไป
                    $stmt = $conn->prepare("INSERT INTO user_accounts (username, password, email) VALUES (?, ?, ?)");
                    $stmt->execute([$username, $password, $email]);
                    $message = "<div class='message' style='background:#d4edda; color:#155724;'>สมัครสมาชิกสำเร็จ! <a href='user_login.php'>คลิกที่นี่เพื่อเข้าสู่ระบบ</a></div>";
                }
            } catch (PDOException $e) {
                $message = "<div class='message' style='background:#f8d7da; color:#721c24;'>Error: " . $e->getMessage() . "</div>";
            }
        } else {
            $message = "<div class='message' style='background:#f8d7da; color:#721c24;'>รหัสผ่านไม่ตรงกัน!</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>สมัครสมาชิก - User</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container" style="max-width: 450px; margin-top: 50px;">
    <h2>👤 สมัครสมาชิกผู้ใช้งานทั่วไป (User Register)</h2>
    <?php echo $message; ?>
    <form method="POST" action="user_register.php">
        <div class="form-group"><label>Username</label><input type="text" class="form-control" name="username" required></div>
        <div class="form-group"><label>Email</label><input type="email" class="form-control" name="email" required></div>
        <div class="form-group"><label>Password</label><input type="password" class="form-control" name="password" required minlength="4"></div>
        <div class="form-group"><label>Confirm Password</label><input type="password" class="form-control" name="confirm_password" required></div>
        <div class="form-actions"><button type="submit" class="btn-submit" style="width: 100%;">สมัครสมาชิก</button></div>
        <div style="text-align: center; margin-top: 15px;"><a href="user_login.php" style="color: #6c757d; font-size: 14px; text-decoration: none;">มีบัญชีอยู่แล้ว? เข้าสู่ระบบที่นี่</a></div>
    </form>
</div>
</body>
</html>