<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FriendGroup extends Model
{
    protected $table = 'friendGroups';

    public function friends()
    {
        return $this->hasMany(Friend::class, 'friendGroup_id', 'id');
    }
}
