<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $guarded = [];

    public function exchange()
    {
        return $this->belongsTo(Exchange::class);
    }
}
