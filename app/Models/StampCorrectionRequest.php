<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Attendance;

class StampCorrectionRequest extends Model
{
    protected $fillable=[
        'user_id',
        'attendance_id',
        'status',
        'remarks',
        'check_in',
        'check_out',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

    public function stampCorrectionRestTimes()
    {
        return $this->hasMany(StampCorrectionRestTime::class);
    }

}
