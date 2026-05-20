<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'attendance_id',
        'status',
    ]

    public function attendances()
    {
        return $this->belongsTo(Attendance::class);
    }

}
