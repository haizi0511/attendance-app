<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\BreakTime;
use App\Models\AttendanceRequest;
use Carbon\Carbon;
use App\Http\Requests\AttendanceUpdateRequest;

class AttendanceController extends Controller
{
    public function index()
    {
        // 今日の勤怠を取得（clock_in で検索）
        $attendance = Attendance::where('user_id', auth()->id())
            ->whereDate('clock_in', Carbon::today())
            ->first();

        return view('user.attendance', compact('attendance'));  
    }

    public function clockIn()
    {
        // 今日すでに出勤しているか
        $exists = Attendance::where('user_id', auth()->id())
            ->whereDate('clock_in', Carbon::today())
            ->exists();

        if ($exists) {
            return redirect('/')->with('error', '今日はすでに出勤済みです');
        }

        Attendance::create([
            'user_id' => auth()->id(),
            'status' => 1, // 勤務中
            'clock_in' => now(),
        ]);

        return redirect('/')->with('success', '出勤しました');
    }

    public function breakIn()
    {
        $attendance = Attendance::where('user_id', auth()->id())
            ->whereDate('clock_in', today())
            ->first();

        // 出勤していない場合
        if (!$attendance || $attendance->status !== 1) {
            return redirect()->back()->with('error', '勤務中のみ休憩開始できます');
        }

        // 休憩開始
        $breakTime = BreakTime::create([
            'break_start' => now(),
        ]);

        $attendance->breakTimes()->attach($breakTime->id);

        // ステータス変更（休憩中）
        $attendance->status = 2;
        $attendance->save();

        return redirect()->back();
    }

    public function breakOut()
    {
        $attendance = Attendance::where('user_id', auth()->id())
            ->whereDate('clock_in', today())
            ->first();

        // 休憩中でなければ終了できない
        if (!$attendance || $attendance->status !== 2) {
            return redirect()->back()->with('error', '休憩中のみ休憩終了できます');
        }

        // 最新の休憩レコード
        $breakTime = $attendance->breakTimes()->latest()->first();

        if (!$breakTime) {
            return redirect()->back()->with('error', '休憩データがありません');
        }

        // 休憩終了
        $breakTime->update([
            'break_end' => now(),
        ]);

        // ステータスを勤務中に戻す
        $attendance->status = 1;
        $attendance->save();

        return redirect()->back();
    }

    public function clockOut()
    {
        $attendance = Attendance::where('user_id', auth()->id())
            ->whereDate('clock_in', today())
            ->first();

        // 勤務中 or 休憩中でなければ退勤できない
        if (!$attendance || !in_array($attendance->status, [1, 2])) {
            return redirect()->back()->with('error', '勤務中のみ退勤できます');
        }

        // 退勤時刻
        $attendance->clock_out = now();
        $attendance->status = 3; // 退勤済
        $attendance->save();

        return redirect()->back()->with('message', 'お疲れ様でした。');
    }

    public function list(Request $request)
    {
        $currentMonth = $request->month
            ? Carbon::parse($request->month)
            : Carbon::now();

        $prevMonth = $currentMonth->copy()->subMonth()->format('Y-m');
        $nextMonth = $currentMonth->copy()->addMonth()->format('Y-m');

        $start = $currentMonth->copy()->startOfMonth();
        $end = $currentMonth->copy()->endOfMonth();

        $attendances = Attendance::where('user_id', auth()->id())
            ->whereBetween('created_at', [$start, $end])
            ->orderBy('created_at')
            ->get();

        return view('user.list', [
            'attendances' => $attendances,
            'currentMonth' => $currentMonth,
            'prevMonth' => $prevMonth,
            'nextMonth' => $nextMonth,
        ]);
    }

    public function show($id)
    {
        $attendance = Attendance::with('breakTimes', 'user')->findOrFail($id);

        return view('user.detail', [
            'attendance' => $attendance,
        ]);
    }

    public function detail($id)
    {
        $attendance = Attendance::with('breakTimes', 'user', 'attendance_requests')->findOrFail($id);

        // ★ 追加：一般ユーザーは自分の勤怠しか見れない
        if (!auth()->user()->isAdmin() && $attendance->user_id !== auth()->id()) {
            abort(403);
        }

        $isPending = $attendance->attendance_requests
            && $attendance->attendance_requests->status === 'pending';

        return view('user.detail', [
            'attendance' => $attendance,
            'isPending' => $isPending,
        ]);
    }

    public function update(AttendanceUpdateRequest $request, $id)
    {
        $attendance = Attendance::with('attendance_requests')->findOrFail($id);

        // すでに承認待ちがある場合は弾く
        if ($attendance->attendance_requests && $attendance->attendance_requests->status === 'pending') {
            return redirect()->route('attendance.detail', $id)
                ->with('message', 'すでに承認待ちの申請があります');
        }

        $attendance->update([
            'clock_in'  => $request->clock_in,
            'clock_out' => $request->clock_out,
            'note'      => $request->note,
        ]);
        // ★ 申請データを作成
        AttendanceRequest::create([
            'attendance_id' => $attendance->id,
            'status'        => 'pending',
            'reason'        => $request->input('reason'), // 申請理由
        ]);

        return redirect()->route('attendance.detail', $id)
            ->with('message', '修正申請を送信しました');
    }

    //管理者画面
    public function admin_index(Request $request)
    {
        // 表示する日付（指定がなければ今日）
        $date = $request->input('date', Carbon::today()->toDateString());

        // 前日・翌日
        $prevDate = Carbon::parse($date)->subDay()->toDateString();
        $nextDate = Carbon::parse($date)->addDay()->toDateString();

        // 管理者用：その日の全ユーザーの勤怠を取得
        $attendances = Attendance::with('user')
            ->whereDate('created_at', $date)
            ->orderBy('user_id')
            ->get();

        return view('admin.index', compact(
            'date',
            'prevDate',
            'nextDate',
            'attendances'
        ));
    }


    public function admin_detail($id)
    {
        $attendance = Attendance::with('breakTimes', 'user', 'attendance_requests')->findOrFail($id);

        // ★ 追加：一般ユーザーは自分の勤怠しか見れない
        if (!auth()->user()->isAdmin() && $attendance->user_id !== auth()->id()) {
            abort(403);
        }

        $isPending = $attendance->attendance_requests
            && $attendance->attendance_requests->status === 'pending';

        return view('admin.detail', [
            'attendance' => $attendance,
            'isPending' => $isPending,
        ]);
    }

    public function admin_update(AttendanceUpdateRequest $request, $id)
    {
        $attendance = Attendance::with('attendance_requests')->findOrFail($id);

        // すでに承認待ちがある場合は弾く
        if ($attendance->attendance_requests && $attendance->attendance_requests->status === 'pending') {
            return redirect()->route('admin.detail', $id)
                ->with('message', 'すでに承認待ちの申請があります');
        }

        $attendance->update([
            'clock_in'  => $request->clock_in,
            'clock_out' => $request->clock_out,
            'note'      => $request->note,
        ]);
        // ★ 申請データを作成
        AttendanceRequest::create([
            'attendance_id' => $attendance->id,
            'status'        => 'pending',
            'reason'        => $request->input('reason'), // 申請理由
        ]);

        return redirect()->route('attendance.detail', $id)
            ->with('message', '修正申請を送信しました');
    }
}