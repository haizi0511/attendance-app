<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;
    protected $fillable = [
        'attendance_id',
        'changed_by',
        'before_clock_in',
        'before_clock_out',
        'before_break1',
        'before_break2',
        'before_note',
        ];

    public function attendances()
    {
        return $this->belongsTo(Attendance::class);
    }

}
