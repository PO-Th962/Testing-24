@extends('layouts.app')

@section('title', 'Admin Dashboard - ระบบจัดการข้อมูล คณะเศรษฐศาสตร์ มช.')
@section('max-width', '1050px')

@section('extra-styles')
<style>
    .admin-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        border-bottom: 2px solid #e2e8f0;
        padding-bottom: 15px;
    }
    .admin-title {
        color: #1e3c72;
        font-weight: 700;
        margin-bottom: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .logout-btn {
        background: #dc3545;
        color: white;
        padding: 10px 18px;
        border-radius: var(--border-radius-md);
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
    }
    .logout-btn:hover {
        background: #bd2130;
        transform: translateY(-1px);
    }
    
    /* Metrics Grid */
    .metrics-grid {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 20px;
        margin-bottom: 35px;
    }
    .metric-card {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        color: white;
        padding: 24px;
        border-radius: var(--border-radius-md);
        box-shadow: 0 4px 15px rgba(30, 60, 114, 0.15);
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .metric-card h3 {
        font-size: 15px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        opacity: 0.85;
        margin-bottom: 10px;
    }
    .metric-value {
        font-size: 42px;
        font-weight: 700;
        line-height: 1.1;
    }
    .courses-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        padding: 20px 24px;
        border-radius: var(--border-radius-md);
    }
    .courses-card h3 {
        color: #1e3c72;
        margin-bottom: 12px;
        font-size: 16px;
        font-weight: 600;
    }
    .course-list {
        list-style: none;
    }
    .course-item {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px dashed #e2e8f0;
        font-size: 14px;
    }
    .course-item:last-child {
        border-bottom: none;
    }
    .course-count {
        background: rgba(30, 60, 114, 0.1);
        color: #1e3c72;
        padding: 2px 10px;
        border-radius: 20px;
        font-weight: 700;
    }

    /* Calculator Section */
    .calc-section {
        background: #f1f8e9;
        border: 1px solid #d0e7b5;
        border-left: 5px solid #7cb342;
        padding: 25px;
        border-radius: var(--border-radius-md);
        margin-bottom: 35px;
    }
    .calc-title {
        color: #33691e;
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .calc-form {
        display: flex;
        gap: 15px;
        align-items: center;
        flex-wrap: wrap;
    }
    .calc-input-wrapper {
        flex: 1;
        min-width: 250px;
    }
    .btn-calc {
        background: #7cb342;
        box-shadow: 0 4px 15px rgba(124, 179, 66, 0.3);
    }
    .btn-calc:hover {
        background: #689f38;
        box-shadow: 0 6px 20px rgba(124, 179, 66, 0.4);
    }
    .btn-calc-clear {
        background: #e2e8f0;
        color: #475569;
    }
    .btn-calc-clear:hover {
        background: #cbd5e1;
    }

    /* Table styling */
    .table-container {
        overflow-x: auto;
        margin-top: 20px;
    }
    .actions-column {
        display: flex;
        justify-content: center;
        gap: 8px;
    }
    .btn-delete {
        background: #ffebee;
        color: #c62828;
        border: 1px solid #ffcdd2;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
    }
    .btn-delete:hover {
        background: #c62828;
        color: white;
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('content')
    <div class="admin-header">
        <h2 class="admin-title">💻 ระบบจัดการสำหรับผู้ดูแลระบบ (Admin Panel)</h2>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="logout-btn" style="border: none; cursor: pointer;">ออกจากระบบ</button>
        </form>
    </div>

    @if(session('success'))
        <div class="message-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="message-error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Metrics -->
    <div class="metrics-grid">
        <div class="metric-card">
            <h3>ผู้ลงทะเบียนทั้งหมด</h3>
            <div class="metric-value">{{ $total_users }} <span style="font-size: 18px; font-weight: normal; opacity: 0.8;">คน</span></div>
        </div>
        <div class="courses-card">
            <h3>สรุปยอดผู้ลงทะเบียนตามรายหลักสูตร</h3>
            <ul class="course-list">
                @forelse($course_summary as $summary)
                    <li class="course-item">
                        <span>{{ $summary->course }}</span>
                        <span class="course-count">{{ $summary->total }} คน</span>
                    </li>
                @empty
                    <li class="course-item" style="color: var(--text-muted);">ยังไม่มีข้อมูลผู้ลงทะเบียน</li>
                @endforelse
            </ul>
        </div>
    </div>

    <!-- Day Calculator -->
    <div class="calc-section">
        <h3 class="calc-title">📊 โปรแกรมคำนวณวันจัดอบรมขั้นต่ำ (สูงสุด 35 คน/วัน)</h3>
        <form method="POST" action="{{ route('admin.calculate') }}" class="calc-form">
            @csrf
            <div class="calc-input-wrapper">
                <input type="number" class="form-control" name="applicants" placeholder="กรอกจำนวนผู้สมัครทั้งหมดที่ต้องการอบรม" required value="{{ $applicantsInput }}">
            </div>
            <button type="submit" class="btn-submit btn-calc" style="width: auto; margin-top: 0;">คำนวณวันอบรม</button>
            <a href="{{ route('admin.dashboard') }}" class="btn-cancel btn-calc-clear">ล้างข้อมูล</a>
        </form>

        @if(!empty($applicantsInput))
            <div style="margin-top: 20px; font-size: 16px; font-weight: 700; color: #33691e;">
                 ผลการคำนวณ: ต้องใช้เวลาอบรมขั้นต่ำทั้งหมด <u>{{ $requiredDays }}</u> วัน
            </div>
            <div style="max-width: 280px; margin: 25px auto 0 auto; height: 280px;">
                <canvas id="adminPieChart"></canvas>
            </div>
        @endif
    </div>

    <!-- Registration List Table -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px;">
        <h3 style="color: #1e3c72; font-weight: 700; font-size: 18px;">รายชื่อผู้ลงทะเบียนเข้าอบรมทั้งหมด</h3>
    </div>
    
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ชื่อ-นามสกุล</th>
                    <th>อีเมล</th>
                    <th>เบอร์โทร</th>
                    <th>หลักสูตร</th>
                    <th style="text-align: center;">วันที่อบรม</th>
                    <th style="text-align: center;">การจัดการ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                    <tr>
                        <td style="font-weight: 600;">{{ $u->fullname }}</td>
                        <td>{{ $u->email }}</td>
                        <td>{{ $u->Tel }}</td>
                        <td style="color: #1e3c72;">{{ $u->course }}</td>
                        <td style="text-align: center;">{{ \Carbon\Carbon::parse($u->class_date)->format('d/m/Y') }}</td>
                        <td style="text-align: center;">
                            <div class="actions-column">
                                <a href="{{ route('admin.delete', ['id' => $u->id]) }}" class="btn-delete" onclick="return confirm('คุณต้องการลบข้อมูลผู้สมัคร {{ $u->fullname }} ใช่หรือไม่?')">ลบข้อมูล</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center" style="color: var(--text-muted); padding: 30px 16px;">ยังไม่มีรายชื่อผู้ลงทะเบียนในระบบค่ะ</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

@section('extra-scripts')
<script>
@if(!empty($chartData))
    const ctx = document.getElementById('adminPieChart').getContext('2d');
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: {!! json_encode(array_keys($chartData)) !!},
            datasets: [{
                data: {!! json_encode(array_values($chartData)) !!},
                backgroundColor: ['#1e3c72', '#7cb342', '#fbbc05', '#ea4335', '#9c27b0'],
                borderWidth: 1
            }]
        },
        options: { 
            responsive: true, 
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        font: {
                            family: 'Sarabun'
                        }
                    }
                }
            }
        }
    });
@endif
</script>
@endsection
