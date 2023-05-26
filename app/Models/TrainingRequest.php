<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'trainee_id',
        'program_id',
        'status'
    ];
    public $incrementing = false;

    protected $primaryKey = [
        'trainee_id',
        'program_id'
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at'
    ];
    protected $dates = ['deleted_at'];

    public function trainee()
    {
        return $this->belongsTo(Trainee::class);
    }
}
