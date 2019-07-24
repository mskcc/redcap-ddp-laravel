<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DataSource extends Model
{
    public function source() {
        return $this->morphTo();
   }
}
