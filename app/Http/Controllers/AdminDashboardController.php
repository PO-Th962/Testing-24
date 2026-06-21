<?php

namespace App\Http\Controllers;

use App\Models\User; // ตาราง users เก็บผู้ลงทะเบียนเข้าอบรม
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    private $maxCapacityPerDay = 35;

    public function index()
    {
        $users = User::orderBy('id', 'desc')->get();
        $total_users = User::count();
        $course_summary = User::select('course', DB::raw('count(*) as total'))
            ->groupBy('course')
            ->get();

        return view('admin.dashboard', [
            'users' => $users,
            'total_users' => $total_users,
            'course_summary' => $course_summary,
            'applicantsInput' => '',
            'requiredDays' => 0,
            'chartData' => [],
        ]);
    }

    public function calculate(Request $request)
    {
        $request->validate([
            'applicants' => 'required|integer|min:0',
        ]);

        $applicantsInput = $request->applicants;
        $totalApplicants = max(0, intval($applicantsInput));

        // คำนวณจำนวนวันจัดอบรม
        $requiredDays = 0;
        if ($totalApplicants > 0) {
            $requiredDays = ceil($totalApplicants / $this->maxCapacityPerDay);
        }

        // เตรียมข้อมูลกราฟวงกลม
        $remaining = $totalApplicants;
        $chartData = [];
        for ($i = 1; $i <= $requiredDays; $i++) {
            if ($remaining >= $this->maxCapacityPerDay) {
                $chartData["วันที่ $i"] = $this->maxCapacityPerDay;
                $remaining -= $this->maxCapacityPerDay;
            } else {
                if ($remaining > 0) {
                    $chartData["วันที่ $i"] = $remaining;
                    $remaining = 0;
                }
            }
        }

        // ดึงข้อมูลหลักเพื่อเรนเดอร์หน้าจอเดิม
        $users = User::orderBy('id', 'desc')->get();
        $total_users = User::count();
        $course_summary = User::select('course', DB::raw('count(*) as total'))
            ->groupBy('course')
            ->get();

        return view('admin.dashboard', [
            'users' => $users,
            'total_users' => $total_users,
            'course_summary' => $course_summary,
            'applicantsInput' => $applicantsInput,
            'requiredDays' => $requiredDays,
            'chartData' => $chartData,
        ]);
    }

    public function delete($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return redirect()->route('admin.dashboard')->with('success', 'ลบข้อมูลผู้ลงทะเบียนสำเร็จแล้วค่ะ');
        }
        return redirect()->route('admin.dashboard')->withErrors(['error' => 'ไม่พบข้อมูลที่ต้องการลบ']);
    }
}
