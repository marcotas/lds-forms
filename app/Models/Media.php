<?php

namespace App\Models;

use Spatie\MediaLibrary\Models\Media as SpatieMedia;

class Media extends SpatieMedia
{
    protected $appends = ['url'];

    public function getUrlAttribute()
    {
        return $this->getFullUrl();
    }
}
