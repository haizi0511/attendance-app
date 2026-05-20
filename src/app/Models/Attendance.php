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
}
