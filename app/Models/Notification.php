<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'user_id',
        'created_at'
    ];


    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
