<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        if (session('admin_logged_in') === true) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $admin = Admin::where('username', $request->username)->first();

        if ($admin && Hash::check($request->password, $admin->password)) {
            session(['admin_logged_in' => true]);
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['error' => 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง!'])->withInput();
    }

    public function showForget()
    {
        return view('admin.forget');
    }

    public function forget(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if ($admin) {
            $token = Str::random(64);
            $expiry = now()->addMinutes(15);

            $admin->update([
                'reset_token' => $token,
                'token_expiry' => $expiry,
            ]);

            $resetLink = route('admin.reset', ['token' => $token]);

            $simulatedEmail = "
                <div style='background: #fff; border: 2px dashed #1a73e8; padding: 20px; margin-top: 20px; border-radius: 8px;'>
                    <h4 style='color: #1a73e8; margin-top: 0;'>📧 [กล่องจำลองระบบส่งอีเมลเข้าสู่ Inbox]</h4>
                    <p><strong>จาก:</strong> security-system@econ.cmu.ac.th</p>
                    <p><strong>ถึง:</strong> " . htmlspecialchars($admin->email) . "</p>
                    <p><strong>หัวข้อ:</strong> ยืนยันคำขอเปลี่ยนรหัสผ่านระบบ Admin</p>
                    <hr style='border:0; border-top:1px solid #ccc; margin:10px 0;'>
                    <p>เรียนผู้ดูแลระบบ, มีคำขอเปลี่ยนรหัสผ่านเข้ามาในระบบ หากคุณเป็นผู้ส่งคำขอนี้ กรุณาคลิกลิงก์ด้านล่างเพื่อตั้งรหัสผ่านใหม่ภายใน 15 นาที:</p>
                    <p style='text-align:center; margin:20px 0;'>
                        <a href='" . $resetLink . "' style='background:#1a73e8; color:white; padding:10px 20px; text-decoration:none; border-radius:5px; font-weight:bold; display:inline-block;'>คลิกที่นี่เพื่อตั้งรหัสผ่านใหม่</a>
                    </p>
                    <small style='color:red;'>*ลิงก์นี้จะหมดอายุในเวลา: " . $expiry->format('Y-m-d H:i:s') . "</small>
                </div>";

            return back()->with([
                'success' => 'ระบบได้ตรวจสอบตัวตนสำเร็จแล้ว! กรุณาตรวจสอบอีเมลจำลองด้านล่าง',
                'simulatedEmail' => $simulatedEmail
            ]);
        }

        return back()->withErrors(['error' => 'ไม่พบที่อยู่อีเมลนี้ในระบบผู้ดูแลระบบ!'])->withInput();
    }

    public function showReset(Request $request)
    {
        $token = $request->query('token');

        if (empty($token)) {
            return redirect()->route('admin.forget')->withErrors(['error' => 'ไม่พบรหัส Token สำหรับการเข้าถึงหน้านี้']);
        }

        $admin = Admin::where('reset_token', $token)
            ->where('token_expiry', '>', now())
            ->first();

        if ($admin) {
            return view('admin.reset', ['token' => $token, 'validToken' => true]);
        }

        return view('admin.reset', ['token' => $token, 'validToken' => false])
            ->withErrors(['error' => 'Token ไม่ถูกต้อง หรือ ลิงก์หมดอายุไปแล้ว (เกิน 15 นาที)']);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'new_password' => 'required|string|min:4',
            'confirm_password' => 'required|string|same:new_password',
        ]);

        $admin = Admin::where('reset_token', $request->token)
            ->where('token_expiry', '>', now())
            ->first();

        if (!$admin) {
            return redirect()->route('admin.forget')->withErrors(['error' => 'Token ไม่ถูกต้อง หรือ ลิงก์หมดอายุไปแล้ว']);
        }

        $admin->update([
            'password' => Hash::make($request->new_password),
            'reset_token' => null,
            'token_expiry' => null,
        ]);

        return redirect()->route('admin.login')->with('success', 'เปลี่ยนรหัสผ่านใหม่สำเร็จแล้วค่ะ! คลิกที่นี่เพื่อล็อกอินใหม่');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('admin_logged_in');
        return redirect()->route('admin.login')->with('success', 'ออกจากระบบแอดมินเรียบร้อยแล้วค่ะ');
    }
}
