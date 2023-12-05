<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminAction extends Model
{
    protected $table = 'admin_actions';
    protected $fillable = ['admin_id','object_type','object_id','type'];
    protected $casts = [
        'content' => 'array',
    ];
}
