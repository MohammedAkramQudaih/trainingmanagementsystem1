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
        'user_id'
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    protected $dates = ['deleted_at'];

   /* public function programs()
    {
        return $this->belongsToMany(
            Program::class, //related model
            'program_trainee',//pivot table
            'trainee_id',//f.k for current model in pivot table
            'program_id',// f.k for related model in pivot table
            'id', //current model key(p.k)
            'id' //related model key(p.k for related model )
        );
    }*/
    public function program()
    {
        return $this->belongsTo(Program::class);
    }
    public function user()
    {
        return $this->belongsTo(User::  class);
    }
}
