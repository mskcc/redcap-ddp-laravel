<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class FieldSource extends Model
{

    public function getQueryFor($id)
    {

        $replaced = Str::replaceLast('= id', "= {$id}", $this->query);
        $replaced = Str::replaceLast('= sid', "= '{$id}'", $replaced);


        return $replaced;
    }

}
