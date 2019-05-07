<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    /**
     * Get the user that owns the file.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
