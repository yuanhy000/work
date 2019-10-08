<?php

namespace App;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Friend extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'friend_id', 'id');
    }
}
