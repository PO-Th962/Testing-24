<!DOCTYPE html>
<html>
<head>
    <title>รหัสผ่าน PIN สำหรับเปลี่ยนรหัสผ่าน</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h2 style="color: #1a73e8; text-align: center;">แจ้งเตือนการเปลี่ยนรหัสผ่านผู้ดูแลระบบ</h2>
        <p style="font-size: 16px; color: #333;">เรียน ผู้ดูแลระบบ,</p>
        <p style="font-size: 16px; color: #333;">มีคำร้องขอเปลี่ยนรหัสผ่านสำหรับบัญชีของคุณ เพื่อดำเนินการต่อ กรุณานำรหัส PIN 6 หลักด้านล่างนี้ไปกรอกในหน้าเว็บไซต์เพื่อยืนยันตัวตน:</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <span style="display: inline-block; background-color: #f0f4f8; padding: 15px 30px; font-size: 32px; font-weight: bold; letter-spacing: 5px; color: #1a73e8; border-radius: 5px; border: 1px dashed #1a73e8;">
                {{ $pin }}
            </span>
        </div>
        
        <p style="font-size: 14px; color: #666; text-align: center;">รหัส PIN นี้จะมีอายุการใช้งานเพียง <strong>15 นาที</strong> เท่านั้น</p>
        <p style="font-size: 14px; color: #666; text-align: center; margin-top: 20px;">หากคุณไม่ได้เป็นผู้ร้องขอการเปลี่ยนรหัสผ่าน กรุณาเพิกเฉยต่ออีเมลฉบับนี้ หรือติดต่อแอดมินระบบหลักทันที</p>
        
        <hr style="border: none; border-top: 1px solid #eee; margin: 30px 0;">
        <p style="font-size: 12px; color: #999; text-align: center;">&copy; {{ date('Y') }} ระบบการจัดการอบรม All Rights Reserved.</p>
    </div>
</body>
</html>
