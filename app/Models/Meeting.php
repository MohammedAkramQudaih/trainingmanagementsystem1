<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject',
        'start_time',
        'end_time',
        'advisor_id',
        'status'
    ];


    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function trainee()
    {
        return $this->belongsTo(Trainee::class);
    }

    public function advisor()
    {
        return $this->belongsTo(Advisor::class);
    }
}
