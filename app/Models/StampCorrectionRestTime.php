<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StampCorrectionRestTime extends Model
{
    protected $fillable = [
        'stamp_correction_request_id',
        'rest_start',
        'rest_end',
    ];

    public function stampCorrectionRequest()
    {
        return $this->belongsTo(StampCorrectionRequest::class);
    }
}