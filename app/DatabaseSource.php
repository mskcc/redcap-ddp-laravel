<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DatabaseSource extends Model
{
    /**
     * Get the database source parent.
     */
    public function dataSource()
    {
        return $this->morphOne(DataSource::class, 'source');
    }
}
