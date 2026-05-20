<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendancesBreak extends Model
{
    use HasFactory;
    protected $fillable = [
        'attendance_id',
        'breaks_id',
        ];

    public function attendances()
    {
        return $this->belongsToMany(Attendance::class);
    }

    public function break_times()
    {
        return $this->belongsToMany(BreakTime::class);
    }
}
