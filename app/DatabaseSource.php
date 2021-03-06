<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DatabaseSource extends Model
{

    protected $with = ['dbType'];

    /**
     * Get the database source parent.
     */
    public function dataSource()
    {
        return $this->morphOne(DataSource::class, 'source');
    }

    /**
     * The type of database source.
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function dbType()
    {
        return $this->hasOne(DatabaseType::class, 'id', 'db_type');
    }
}
