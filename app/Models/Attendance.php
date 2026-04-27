<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\RestTime;
use App\Models\StampCorrectionRequest;

class Attendance extends Model
{
    protected $fillable=[
        'user_id',
        'date',
        'check_in',
        'check_out',
        'status',
        'remarks',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function restTimes()
    {
        return $this->hasMany(RestTime::class);
    }

    public function stampCorrectionRequest()
    {
        return $this->hasOne
        (StampCorrectionRequest::class);
    }
}
