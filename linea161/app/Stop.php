<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Stop extends Model
{
    public function branch()
    {
        return $this->belongsTo('App\Branch');
    }
}
