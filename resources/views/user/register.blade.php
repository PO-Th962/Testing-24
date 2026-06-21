@extends('layouts.app')

@section('title', 'สมัครสมาชิกผู้ใช้งาน - ระบบลงทะเบียนอบรม คณะเศรษฐศาสตร์ มช.')

@section('content')
    <h2>สมัครสมาชิกผู้ใช้งานทั่วไป</h2>
    <p class="text-center" style="color: var(--text-muted); margin-bottom: 25px; font-size: 14px;">สร้างบัญชีผู้ใช้งานเพื่อเข้าลงทะเบียนเข้าร่วมโครงการอบรม</p>

    @if($errors->has('error'))
        <div class="message-error">
            {{ $errors->first('error') }}
        </div>
    @endif

    @if($errors->any() && !$errors->has('error'))
        <div class="message-error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('user.register.submit') }}">
        @csrf
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" class="form-control" name="username" value="{{ old('username') }}" required placeholder="ตั้งชื่อผู้ใช้งาน">
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" class="form-control" name="email" value="{{ old('email') }}" required placeholder="example@domain.com">
        </div>
        
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" class="form-control" name="password" required minlength="4" placeholder="รหัสผ่านอย่างน้อย 4 ตัวอักษร">
        </div>

        <div class="form-group">
            <label for="confirm_password">Confirm Password</label>
            <input type="password" id="confirm_password" class="form-control" name="confirm_password" required placeholder="กรอกรหัสผ่านอีกครั้ง">
        </div>

        <button type="submit" class="btn-submit" style="margin-top: 10px;">สมัครสมาชิก</button>

        <div class="text-center mt-3">
            <a href="{{ route('user.login') }}" class="link-accent" style="color: var(--text-muted);">มีบัญชีผู้ใช้อยู่แล้ว? เข้าสู่ระบบที่นี่</a>
        </div>
    </form>
@endsection
