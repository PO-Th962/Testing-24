@extends('layouts.app')

@section('title', 'ตั้งรหัสผ่านใหม่สำหรับ Admin - คณะเศรษฐศาสตร์ มช.')

@section('content')
    <h2 style="color: #c62828;"> ตั้งรหัสผ่านใหม่สำหรับ Admin</h2>
    <p style="color: var(--text-muted); margin-bottom: 25px; font-size: 14px; text-align: center;">
        กรุณากรอกรหัสผ่านใหม่สำหรับบัญชีผู้ดูแลระบบของคุณ
    </p>

    @if($errors->has('error'))
        <div class="message-error" style="background: linear-gradient(135deg, #ffebee 0%, #ffcdd2 100%); color: #c62828;">
            {{ $errors->first('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="message-success">
            {{ session('success') }}
        </div>
    @endif

    @if($validToken)
        <form method="POST" action="{{ route('admin.reset.submit') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group">
                <label for="new_password">รหัสผ่านใหม่ (New Password)</label>
                <input type="password" id="new_password" class="form-control" name="new_password" required minlength="4" placeholder="รหัสผ่านใหม่อย่างน้อย 4 ตัวอักษร">
            </div>

            <div class="form-group">
                <label for="confirm_password">ยืนยันรหัสผ่านใหม่ (Confirm New Password)</label>
                <input type="password" id="confirm_password" class="form-control" name="confirm_password" required minlength="4" placeholder="กรอกรหัสผ่านใหม่อีกครั้ง">
            </div>

            <button type="submit" class="btn-submit" style="background: linear-gradient(135deg, #c62828 0%, #b71c1c 100%); box-shadow: 0 4px 15px rgba(198, 40, 40, 0.3); margin-top: 10px;">บันทึกและเปลี่ยนรหัสผ่าน</button>
        </form>
    @else
        <div class="text-center mt-3">
            <a href="{{ route('admin.forget') }}" class="btn-cancel">กลับไปยังหน้ากู้คืนรหัสผ่าน</a>
        </div>
    @endif
@endsection
