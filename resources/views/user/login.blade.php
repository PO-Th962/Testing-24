@extends('layouts.app')

@section('title', 'เข้าสู่ระบบผู้ใช้งาน - ระบบลงทะเบียนอบรม คณะเศรษฐศาสตร์ มช.')

@section('content')
    <h2>🔑 เข้าสู่ระบบผู้ใช้งาน</h2>
    <p class="text-center" style="color: var(--text-muted); margin-bottom: 25px; font-size: 14px;">กรุณากรอกบัญชีผู้ใช้และรหัสผ่านเพื่อเข้าใช้งานระบบลงทะเบียน</p>

    @if($errors->has('error'))
        <div class="message-error">
            {{ $errors->first('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="message-success">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('user.login.submit') }}">
        @csrf
        <div class="form-group">
            <label for="username">ชื่อผู้ใช้งาน (Username)</label>
            <input type="text" id="username" class="form-control" name="username" value="{{ old('username') }}" required autofocus placeholder="กรอกชื่อผู้ใช้งาน">
        </div>
        
        <div class="form-group">
            <label for="password">รหัสผ่าน (Password)</label>
            <input type="password" id="password" class="form-control" name="password" required placeholder="กรอกรหัสผ่าน">
        </div>

        <button type="submit" class="btn-submit" style="margin-top: 10px;">เข้าสู่ระบบ</button>

        <div class="text-center mt-3">
            <a href="{{ route('user.register') }}" class="link-accent">ยังไม่มีบัญชีใช่หรือไม่? สมัครสมาชิกที่นี่</a>
        </div>
    </form>
@endsection
