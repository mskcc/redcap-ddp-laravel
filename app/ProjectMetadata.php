<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectMetadata extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * The field source that backs this project metadata element.
     */
    public function fieldSource()
    {
        return $this->belongsTo(FieldSource::class);
    }
}
