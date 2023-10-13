<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Todo extends Model
{
    use SoftDeletes;

    protected $table = 'to_do_ihor';

    protected $fillable = [
        'title',
        'description',
    ];

    protected $hidden = ['deleted_at'];
}
