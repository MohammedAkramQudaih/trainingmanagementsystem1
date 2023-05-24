<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
//use Illuminate\Foundation\Auth\User as Authenticatable;


class Advisor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'address',
        'gender',
        'discipline_id',
        'user_id'
    ];
    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at'
    ];
    protected $dates = ['deleted_at'];
    /*protected $appends = [
        'discipline_name'
    ];*/
    //discipline_name
    /* public function getDisciplineNameAttribute()
     {
         if ($this->discipline) {
             return $this->discipline->name;
         } else {
             return 'No discipline assigned';
         }
     }*/
    public function discipline()
    {
        return $this->belongsTo('App\Models\Discipline');
    }
    public function programs()
    {
        return $this->hasMany(Program::class);
    }
    public function trainees($programId)
    {
        return $this->belongsToMany(Trainee::class, 'program_trainee')
            ->wherePivot('program_id', $programId);
    }
//    public function user()
//    {
//        return $this->belongsTo(User::class);
//    }
}
