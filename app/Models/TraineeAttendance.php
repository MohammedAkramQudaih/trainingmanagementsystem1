<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TraineeAttendance extends Model
{
    use HasFactory;

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    protected $fillable = [
        'trainee_id',
        'comments',
        'date',
        'attendance_status'
    ];

    public function trainee()
    {
        return $this->belongsTo(Trainee::class);
    }
}
