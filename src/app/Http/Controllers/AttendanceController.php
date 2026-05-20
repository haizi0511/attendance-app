<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;

class AttendanceController extends Controller
{
    public function index()
    {
        $attendance = Attendance::where('user_id', auth()->id())
            ->whereDate('created_at', today())
            ->first();
        return view('user.attendance', compact('attendance'));
    }

    public function clockIn()
    {
    // 今日のレコードが既にある場合は出勤不可
        $exists = Attendance::where('user_id', auth()->id())
            ->whereDate('created_at', today())
            ->exists();

        if ($exists) {
            return redirect('/')->with('error', '今日はすでに出勤済みです');
        }

        Attendance::create([
            'user_id' => auth()->id(),
            'status' => 1,
            'clock_in' => now(),
        ]);

        return redirect('/')->with('success', '出勤しました');
    }

    public function clockOut()
    {
        $attendance = Attendance::where('user_id', auth()->id())
            ->whereDate('created_at', today())
            ->first();
        return view('user.attendance', compact('attendance'));
    }

    public function breakIn()
    {
        $attendance = Attendance::where('user_id', auth()->id())
            ->whereDate('created_at', today())
            ->first();
        return view('user.attendance', compact('attendance'));
    }

    public function breakOut()
    {
        $attendance = Attendance::where('user_id', auth()->id())
            ->whereDate('created_at', today())
            ->first();
        return view('user.attendance', compact('attendance'));
    }
}
