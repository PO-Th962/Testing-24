<?php
session_start();
include 'db.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT * FROM user_accounts WHERE username = ? AND password = ?");
    $stmt->execute([$username, $password]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['user_logged_in'] = true;
        $_SESSION['user_name'] = $user['username'];
        header('Location: index.php'); // เข้าสู่ระบบสำเร็จ พาไปหน้ากรอกฟอร์มลงทะเบียน
        exit;
    } else {
        $error = 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง!';
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>เข้าสู่ระบบ - User</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container" style="max-width: 400px; margin-top: 100px;">
    <h2>🔑 เข้าสู่ระบบผู้ใช้งาน (User Login)</h2>
    <?php if($error): ?> <div class="message" style="background:#f8d7da; color:#721c24;"><?php echo $error; ?></div> <?php endif; ?>
    <form method="POST" action="user_login.php">
        <div class="form-group"><label>Username</label><input type="text" class="form-control" name="username" required></div>
        <div class="form-group"><label>Password</label><input type="password" class="form-control" name="password" required></div>
        <div class="form-actions"><button type="submit" class="btn-submit" style="width: 100%;">Login</button></div>
        <div style="text-align: center; margin-top: 15px;">
            <a href="user_register.php" style="color: #1a73e8; font-size: 14px; text-decoration: none;">ยังไม่มีบัญชี? สมัครสมาชิกที่นี่</a>
        </div>
    </form>
</div>
</body>
</html>