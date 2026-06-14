<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttendanceRequest;
use App\Models\Attendance;

class AttendanceRequestController extends Controller
{
    public function index()
    {
        $requests = AttendanceRequest::where('status', 'pending')
            ->whereHas('attendance', function ($q) {
                $q->where('user_id', auth()->id());
            })
            ->with('attendance.user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.request', [
            'requests' => $requests,
            'activeTab' => 'pending',
        ]);
    }

    // 承認済み
    public function approved()
    {
        $requests = AttendanceRequest::where('status', 'approved')
            ->whereHas('attendance', function ($q) {
                $q->where('user_id', auth()->id());
            })
            ->with('attendance.user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.request', [
            'requests' => $requests,
            'activeTab' => 'approved',
        ]);
    }

    public function admin_index()
    {
        // 承認待ち
        $requests = AttendanceRequest::where('status', 'pending')
            ->with('attendance.user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.request', [
            'requests' => $requests,
            'activeTab' => 'pending',
        ]);
    }

    public function admin_tab()
    {
        // ★ 承認済みを取得する
        $requests = AttendanceRequest::where('status', 'approved')
            ->with('attendance.user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.request', [
            'requests' => $requests,
            'activeTab' => 'approved',
        ]);
    }

    public function show($id)
    {
        $req = AttendanceRequest::with('attendance.user', 'attendance.breakTimes')
            ->findOrFail($id);

        return view('admin.request_detail', compact('req'));
    }

    public function update($id)
    {
        $req = AttendanceRequest::findOrFail($id);

        // ステータス更新
        $req->status = 'approved';
        $req->save();

        return redirect()
            ->route('admin.request.show', $id);
    }
}
