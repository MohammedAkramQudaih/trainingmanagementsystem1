<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Discipline extends Model
{
    use HasFactory, SoftDeletes;
    protected $hidden = [
        'created_at','updated_at', 'deleted_at'
    ];

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];
    protected $dates = ['deleted_at'];

    public function advisors()
    {
        return $this->hasMany('App\Models\Advisor');
    }

    public function programs()
    {
        return $this->hasMany('App\Models\Advisor');
    }
}
