<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    public $guarded = [];

    public function subject() {
        return $this->morphTo();
    }
}
