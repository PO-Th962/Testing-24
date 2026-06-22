@extends('layouts.app')

@section('title', 'ยืนยันรหัส PIN - Admin Portal คณะเศรษฐศาสตร์ มช.')

@section('content')
    <h2 style="color: #c62828;">ยืนยันรหัส PIN 6 หลัก</h2>
    <p style="color: var(--text-muted); margin-bottom: 25px; font-size: 14px; text-align: center;">
        กรุณากรอกอีเมลและรหัส PIN 6 หลักที่คุณได้รับทางอีเมล เพื่อยืนยันการตั้งรหัสผ่านใหม่
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

    <form method="POST" action="{{ route('admin.verify_pin.submit') }}">
        @csrf
        
        <div class="form-group">
            <label for="email">อีเมลของคุณ</label>
            <input type="email" id="email" class="form-control" name="email" value="{{ $email ?? old('email') }}" required readonly>
        </div>

        <div class="form-group">
            <label for="pin">รหัส PIN 6 หลัก</label>
            <input type="text" id="pin" class="form-control" name="pin" required maxlength="6" pattern="\d{6}" placeholder="123456" style="letter-spacing: 5px; font-size: 20px; text-align: center;">
            <small style="color: var(--text-muted); display: block; margin-top: 5px; text-align: center;">*รหัสผ่านมีอายุ 15 นาที</small>
        </div>

        <button type="submit" class="btn-submit" style="background: linear-gradient(135deg, #c62828 0%, #b71c1c 100%); box-shadow: 0 4px 15px rgba(198, 40, 40, 0.3); margin-top: 10px;">ตรวจสอบรหัส PIN</button>

        <div class="text-center mt-3">
            <a href="{{ route('admin.forget') }}" class="link-accent" style="color: var(--text-muted);">ขอรหัส PIN ใหม่</a>
        </div>
    </form>
@endsection
