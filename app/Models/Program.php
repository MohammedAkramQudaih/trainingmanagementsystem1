<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Program extends Model
{
    use HasFactory;
    use SoftDeletes;

//    public $table = "programs";

    protected $fillable = [
        'title',
        'description',
        'discipline_id',
        'logo',
        'capacity',
        'company',
    ];



    protected $dates = ['deleted_at'];

    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at'
    ];
    public function discipline()
    {
        return $this->belongsTo(Discipline::class);
    }
//    public function trainees()
//    {
//        return $this->belongsToMany(Trainee::class);
//    }


    public function trainees()
    {
        return $this->belongsToMany(Trainee::class, 'program_trainee');
    }

    public function advisor()
    {
        return $this->belongsTo(Advisor::class);
    }
}
