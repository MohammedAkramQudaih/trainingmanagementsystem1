<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoredFile extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'fileName',
        'fileType',
        'fileSize',
        'user_id',
        'fileUrl'
    ];

    protected $dates = ['deleted_at'];

    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at'
    ];


//    public function user()
//    {
//        return $this->belongsTo(User::class);
//    }

}
