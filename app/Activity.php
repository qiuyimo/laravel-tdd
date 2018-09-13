<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    // todo.
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function subject()
    {
        return $this->morphTo();
    }
}
