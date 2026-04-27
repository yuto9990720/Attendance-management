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
        'remarks'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

}
