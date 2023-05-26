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
        'advisor_id  '
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
/*
 *             Program::class, //related model
            'program_trainee',//pivot table
            'trainee_id',//f.k for current model in pivot table
            'program_id',// f.k for related model in pivot table
            'id', //current model key(p.k)
            'id' //related model key(p.k for related model )
 */

    public function trainees()
    {
        return $this->belongsToMany(
            Trainee::class,
            'program_trainee',
            'program_id',
            'trainee_id',
            'id',
            'id'
        );
    }

    public function advisor()
    {
        return $this->belongsTo(Advisor::class);
    }
}
