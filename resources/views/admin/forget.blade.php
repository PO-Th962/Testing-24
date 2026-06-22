@extends('layouts.app')

@section('title', 'ลืมรหัสผ่านผู้ดูแลระบบ - Admin Portal คณะเศรษฐศาสตร์ มช.')

@section('content')
    <h2 style="color: #c62828;">กู้คืนรหัสผ่านผู้ดูแลระบบ</h2>
    <p style="color: var(--text-muted); margin-bottom: 25px; font-size: 14px; text-align: center;">
        กรุณากรอกอีเมลของ Admin ที่ลงทะเบียนไว้เพื่อรับรหัส PIN 6 หลัก สำหรับตั้งรหัสผ่านใหม่
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

    <form method="POST" action="{{ route('admin.forget.submit') }}">
        @csrf
        <div class="form-group">
            <label for="email">อีเมลของ Admin</label>
            <input type="email" id="email" class="form-control" name="email" value="{{ old('email') }}" required placeholder="example@domain.com">
        </div>

        <button type="submit" class="btn-submit" style="background: linear-gradient(135deg, #c62828 0%, #b71c1c 100%); box-shadow: 0 4px 15px rgba(198, 40, 40, 0.3); margin-top: 10px;">ส่งรหัส PIN ไปยังอีเมล</button>

        <div class="text-center mt-3">
            <a href="{{ route('admin.login') }}" class="link-accent" style="color: var(--text-muted);">ย้อนกลับไปหน้าเข้าสู่ระบบ</a>
        </div>
    </form>
@endsection
