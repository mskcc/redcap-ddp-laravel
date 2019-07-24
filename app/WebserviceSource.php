<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WebserviceSource extends Model
{
    /**
     * Get the webservice source parent.
     */
    public function dataSource()
    {
        return $this->morphOne(DataSource::class, 'source');
    }
}
