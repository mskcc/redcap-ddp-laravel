<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ValueMapping extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * The field source this value mapping is for.
     */
    public function fieldSource()
    {
        return $this->belongsTo(FieldSource::class);
    }
}
