@extends('layouts.app')

@section('title', 'เข้าสู่ระบบผู้ดูแลระบบ - Admin Portal คณะเศรษฐศาสตร์ มช.')

@section('content')
    <h2 style="color: #c62828;">เข้าสู่ระบบ Admin</h2>
    <p class="text-center" style="color: var(--text-muted); margin-bottom: 25px; font-size: 14px;">ส่วนควบคุมระบบงานสำหรับเจ้าหน้าที่และผู้ดูแลระบบหลัก</p>

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

    <form method="POST" action="{{ route('admin.login.submit') }}">
        @csrf
        <div class="form-group">
            <label for="username">ชื่อบัญชีผู้ดูแล (Admin Username)</label>
            <input type="text" id="username" class="form-control" name="username" value="{{ old('username') }}" required autofocus placeholder="เช่น admin">
        </div>
        
        <div class="form-group">
            <label for="password">รหัสผ่าน (Password)</label>
            <input type="password" id="password" class="form-control" name="password" required placeholder="กรอกรหัสผ่านผู้ดูแลระบบ">
        </div>

        <button type="submit" class="btn-submit" style="background: linear-gradient(135deg, #c62828 0%, #b71c1c 100%); box-shadow: 0 4px 15px rgba(198, 40, 40, 0.3); margin-top: 10px;">เข้าสู่ระบบ Admin</button>

        <div class="text-center mt-3">
            <a href="{{ route('admin.forget') }}" class="link-accent" style="color: #c62828;">ลืมรหัสผ่านผู้ดูแลระบบ? กู้คืนที่นี่</a>
        </div>
    </form>
@endsection
