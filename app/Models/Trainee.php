<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trainee extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'address',
        'university_name',
        'university_id',
        'gender',
        'status',
        'trainee_id',
        'bio',
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    protected $dates = ['deleted_at'];

    public function programs()
    {
        return $this->belongsToMany(Program::class, 'program_trainee');
    }

}
