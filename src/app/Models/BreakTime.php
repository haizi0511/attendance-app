<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BreakTime extends Model
{
    use HasFactory;
    protected $fillable = [
        'break_start',
        'break_end',
        ];

    public function attendances()
    {
        return $this->belongsToMany(Attendance::class, 'attendances_breaks');
    }

    protected $casts = [
        'break_start' => 'datetime',
        'break_end' => 'datetime',
        ];
}
