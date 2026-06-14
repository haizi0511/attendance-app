<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Models\Attendance;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UserController extends Controller
{
    public function showRegisterForm()
    {
        return view('user.register');
    }

    public function register(RegisterRequest $request)
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 0,
        ]);

        return redirect('/')->with('success', '登録完了');
    }

    public function showloginForm()
    {
        return view('user.login');
    }

    public function index()
    {
        $staffs = User::where('role', 'user')->get();
        return view('admin.staff_index', compact('staffs'));
    }
    public function detail($id, Request $request)
    {
        $user = User::findOrFail($id);

        // 現在の月（指定がなければ今月）
        $currentMonth = $request->month
            ? Carbon::parse($request->month)
            : Carbon::now();

        // 前月・次月
        $prevMonth = $currentMonth->copy()->subMonth()->format('Y-m');
        $nextMonth = $currentMonth->copy()->addMonth()->format('Y-m');

        // 月の範囲
        $start = $currentMonth->copy()->startOfMonth();
        $end   = $currentMonth->copy()->endOfMonth();

        // 勤怠データ
        $attendances = Attendance::with('breakTimes')
            ->where('user_id', $user->id)
            ->whereBetween('created_at', [$start, $end])
            ->orderBy('created_at')
            ->get();

        return view('admin.staff_detail', [
            'user'         => $user,
            'attendances'  => $attendances,
            'currentMonth' => $currentMonth,
            'prevMonth'    => $prevMonth,
            'nextMonth'    => $nextMonth,
        ]);
    }
    public function StaffCsv($id, Request $request)
    {
        $user = User::findOrFail($id);

        $currentMonth = $request->month
            ? Carbon::parse($request->month)
            : Carbon::now();

        $start = $currentMonth->copy()->startOfMonth();
        $end   = $currentMonth->copy()->endOfMonth();

        $attendances = Attendance::with('breakTimes')
            ->where('user_id', $user->id)
            ->whereBetween('created_at', [$start, $end])
            ->orderBy('created_at')
            ->get();

        $fileName = $user->name . '_' . $currentMonth->format('Y_m') . '_attendance.csv';

        $response = new StreamedResponse(function () use ($attendances) {

            $stream = fopen('php://output', 'w');

            // Excel で文字化けしないように BOM を付ける（SJIS の場合は不要）
            // fputs($stream, "\xEF\xBB\xBF"); ← UTF-8 の場合のみ

            // ヘッダー行
            $header = ['日付', '出勤', '退勤', '休憩', '合計'];
            fputcsv($stream, array_map(fn($v) => mb_convert_encoding($v, 'SJIS-win', 'UTF-8'), $header));

            foreach ($attendances as $attendance) {

                $breaks = $attendance->breakTimes->map(function ($b) {
                    return optional($b->break_start)->format('H:i') . '〜' . optional($b->break_end)->format('H:i');
                })->implode(' / ');

                $row = [
                    $attendance->created_at->format('Y-m-d'),
                    optional($attendance->clock_in)->format('H:i'),
                    optional($attendance->clock_out)->format('H:i'),
                    $attendance->total_break_time,
                    $attendance->total_working_hours,
                ];

                // ★ ここで SJIS に変換して書き込む
                $row = array_map(fn($v) => mb_convert_encoding($v, 'SJIS-win', 'UTF-8'), $row);

                fputcsv($stream, $row);
            }

            fclose($stream);
        });

        $response->headers->set('Content-Type', 'text/csv; charset=Shift_JIS');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $fileName . '"');

        return $response;
    }
}