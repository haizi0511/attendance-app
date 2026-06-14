<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'clock_in',
        'clock_out',
        'note',
        'status',
        ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attendance_requests()
    {
        return $this->hasOne(AttendanceRequest::class);
    }

    public function attendances_breaks()
    {
        return $this->belongsToMany(AttendancesBreak::class);
    }

        public function logs()
    {
        return $this->belongsTo(Log::class);
    }
        public function breakTimes()
    {
        return $this->belongsToMany(BreakTime::class, 'attendances_breaks');
    }

    protected $casts = [
        'clock_in' => 'datetime:H:i:s',
        'clock_out' => 'datetime:H:i:s',
    ];

    public function getTotalBreakTimeAttribute()
    {
        $total = 0;

        foreach ($this->breakTimes as $break) {
            if ($break->break_start && $break->break_end) {
                $total += $break->break_end->diffInMinutes($break->break_start);
            }
        }

        $hours = floor($total / 60);
        $minutes = $total % 60;

        return sprintf('%d:%02d', $hours, $minutes);
    }

    public function getWorkTotalAttribute()
    {
        if (!$this->clock_in || !$this->clock_out) {
            return '0:00';
        }

        // 出勤〜退勤の総分数
        $totalMinutes = $this->clock_out->diffInMinutes($this->clock_in);

        // 休憩合計（分）
        $breakMinutes = 0;
        foreach ($this->breakTimes as $break) {
            if ($break->break_start && $break->break_end) {
                $breakMinutes += $break->break_end->diffInMinutes($break->break_start);
            }
        }

        // 労働時間（分）
        $workMinutes = $totalMinutes - $breakMinutes;
        if ($workMinutes < 0) $workMinutes = 0;

        // H:i 形式に変換
        $hours = floor($workMinutes / 60);
        $minutes = $workMinutes % 60;

        return sprintf('%d:%02d', $hours, $minutes);
    }
}
