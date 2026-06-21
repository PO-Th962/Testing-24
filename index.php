<?php
ob_start(); 
include 'db.php';

$message = '';
$editUser = null;

// ส่วนการลบข้อมูล
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    try {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        header("Location: index.php?msg=deleted");
        exit;
    } catch (PDOException $e) { $message = "Error: " . $e->getMessage(); }
}

// ส่วนดึงข้อมูลมาแก้ไข
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $editUser = $stmt->fetch();
}

// ส่วนบันทึกและอัปเดตข้อมูล
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $tel = trim($_POST['Tel'] ?? '');
    $course = trim($_POST['course'] ?? '');
    $class_date = trim($_POST['class_date'] ?? '');
    // เปลี่ยนแปลง: รับค่า PDPA จากฟอร์ม
    $pdpa_consent = isset($_POST['pdpa_consent']) ? 1 : 0; 
    $id = isset($_POST['id']) ? (int)$_POST['id'] : null;

    if ($name != '' && $email != '' && $tel != '' && $course != '' && $class_date != '') {
        try {
            if ($id) {
                // อัปเดตข้อมูลเดิม
                $stmt = $conn->prepare("UPDATE users SET fullname = ?, email = ?, Tel = ?, course = ?, class_date = ? WHERE id = ?");
                $stmt->execute([$name, $email, $tel, $course, $class_date, $id]);
                header("Location: index.php?msg=updated");
            } else {
                // เปลี่ยนแปลง: เพิ่มข้อมูลใหม่พร้อมสถานะ PDPA
                $stmt = $conn->prepare("INSERT INTO users (fullname, email, Tel, course, class_date, pdpa_consent) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$name, $email, $tel, $course, $class_date, $pdpa_consent]);
                header("Location: index.php?msg=created");
            }
            exit;
        } catch (PDOException $e) { $message = "Error: " . $e->getMessage(); }
    } else {
        $message = "กรุณากรอกข้อมูลให้ครบถ้วน";
    }
}

// ดึงข้อมูลทั้งหมดมาแสดงในตาราง
$users = $conn->query("SELECT * FROM users ORDER BY id DESC")->fetchAll();

// เปลี่ยนแปลง: คำนวณข้อมูลสำหรับ Dashboard
$total_users = $conn->query("SELECT COUNT(*) FROM users")->fetchColumn();
$course_summary = $conn->query("SELECT course, COUNT(*) as total FROM users GROUP BY course")->fetchAll();

if (isset($_GET['msg'])) {
    $msgs = ['created'=>'เพิ่มข้อมูลสำเร็จ', 'updated'=>'แก้ไขข้อมูลสำเร็จ', 'deleted'=>'ลบข้อมูลสำเร็จ'];
    $message = $msgs[$_GET['msg']] ?? '';
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ระบบจัดการข้อมูล - CMU AMS</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    
    <div style="background: #e3f2fd; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 5px solid #1a73e8;">
        <h3 style="color: #1a73e8; margin-bottom: 10px;"> Dashboard สรุปข้อมูลการลงทะเบียน</h3>
        <p>จำนวนผู้ลงทะเบียนทั้งหมด: <strong><?php echo $total_users; ?></strong> คน</p>
        <ul style="margin-top: 10px; margin-left: 20px;">
            <?php foreach($course_summary as $summary): ?>
                <li><?php echo htmlspecialchars($summary['course']); ?> : <strong><?php echo $summary['total']; ?></strong> คน</li>
            <?php endforeach; ?>
        </ul>
    </div>

    <h2><?php echo $editUser ? 'แก้ไขข้อมูล' : 'ลงทะเบียนอบรม'; ?></h2>
    
    <?php if($message): ?> <div class="message"><?php echo $message; ?></div> <?php endif; ?>

    <form method="POST" action="index.php">
        <?php if($editUser): ?> 
            <input type="hidden" name="id" value="<?php echo $editUser['id']; ?>"> 
        <?php endif; ?>
        
        <div class="form-group">
            <label>ชื่อ-นามสกุล</label>
            <input type="text" class="form-control" name="fullname" required value="<?php echo $editUser['fullname'] ?? ''; ?>">
        </div>
        <div class="form-group">
            <label>อีเมล</label>
            <input type="email" class="form-control" name="email" required value="<?php echo $editUser['email'] ?? ''; ?>">
        </div>
        <div class="form-group">
            <label>หมายเลขโทรศัพท์</label>
            <input type="text" class="form-control" name="Tel" required value="<?php echo $editUser['Tel'] ?? ''; ?>">
        </div>
        <div class="form-group">
            <label>หลักสูตรการอบรม</label>
            <select class="form-control" name="course" required>
                <option value="">เลือกหลักสูตร</option>
                <option value="หลักสูตรการช่วยเหลือผู้ป่วยเบื้องต้น" <?php echo ($editUser['course'] ?? '') === 'หลักสูตรการช่วยเหลือผู้ป่วยเบื้องต้น' ? 'selected' : ''; ?>>หลักสูตรการช่วยเหลือผู้ป่วยเบื้องต้น</option>
                <option value="หลักสูตรการใช้เครื่องมือทางการแพทย์" <?php echo ($editUser['course'] ?? '') === 'หลักสูตรการใช้เครื่องมือทางการแพทย์' ? 'selected' : ''; ?>>หลักสูตรการใช้เครื่องมือทางการแพทย์</option>
                <option value="หลักสูตรการรักษาแผลติดเชื้อ" <?php echo ($editUser['course'] ?? '') === 'หลักสูตรการรักษาแผลติดเชื้อ' ? 'selected' : ''; ?>>หลักสูตรการรักษาแผลติดเชื้อ</option>
            </select>
        </div>
        <div class="form-group">
            <label>เลือกวันที่เข้าอบรม</label>
            <input type="date" class="form-control" name="class_date" required value="<?php echo $editUser['class_date'] ?? ''; ?>">
        </div>

        <?php if(!$editUser): ?>
        <div class="form-group" style="background: #fff3cd; padding: 10px; border-radius: 5px;">
            <label style="font-weight: normal; font-size: 14px;">
                <input type="checkbox" name="pdpa_consent" value="1" required>
                ฉันยินยอมให้ประมวลผลข้อมูลส่วนบุคคลตาม พ.ร.บ. คุ้มครองข้อมูลส่วนบุคคล (PDPA)
            </label>
        </div>
        <?php endif; ?>
        
        <div class="form-actions">
            <button type="submit" class="btn-submit">บันทึกข้อมูล</button>
            <?php if($editUser): ?> <a href="index.php" class="btn-cancel">ยกเลิก</a> <?php endif; ?>
        </div>
    </form>

    <table>
        <thead>
            <tr>
                <th>ชื่อ-นามสกุล</th>
                <th>อีเมล</th>
                <th>เบอร์โทร</th>
                <th>หลักสูตร</th>
                <th>วันที่เข้าอบรม</th>
                <th class="center">จัดการ</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($users as $u): ?>
            <tr>
                <td><?php echo htmlspecialchars($u['fullname'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($u['email'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($u['Tel'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($u['course'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($u['class_date'] ?? ''); ?></td>
                <td class="actions center">
                    <a href="index.php?action=edit&id=<?php echo $u['id']; ?>" class="edit">แก้ไข</a>
                    <a href="index.php?action=delete&id=<?php echo $u['id']; ?>" class="delete" onclick="return confirm('ยืนยันการลบข้อมูล?')">ลบ</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
<?php ob_end_flush(); ?>