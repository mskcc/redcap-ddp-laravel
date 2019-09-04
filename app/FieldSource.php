<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class FieldSource extends Model
{

    public function getQueryFor($id)
    {

        $replaced = Str::replaceLast('= id', "= {$id}", $this->query);
        $replaced = Str::replaceLast('= sid', "= '{$id}'", $replaced);

        return $replaced;
    }

    /**
     * @return BelongsTo
     * The field source that backs this project metadata element.
     */
    public function dataSource()
    {
        return $this->belongsTo(DataSource::class);
    }

}
