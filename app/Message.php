<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'messages';

    public function messageFrom() {
        return $this->belongsTo('App\User', 'from');
    }
}
