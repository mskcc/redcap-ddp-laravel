<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class FieldSource extends Model
{

    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @return BelongsTo
     * The field source that backs this project metadata element.
     */
    public function dataSource()
    {
        return $this->belongsTo(DataSource::class);
    }

    public function valueMappings()
    {
    	return $this->hasMany(ValueMapping::class);
    }
}
