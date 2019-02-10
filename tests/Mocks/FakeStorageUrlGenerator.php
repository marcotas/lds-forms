<?php

namespace Tests\Mocks;

use Spatie\MediaLibrary\UrlGenerator\LocalUrlGenerator;

class FakeStorageUrlGenerator extends LocalUrlGenerator
{
    protected function getBaseMediaDirectoryUrl() : string
    {
        if ($diskUrl = $this->config->get("filesystems.disks.{$this->media->disk}.url")) {
            return str_replace(url('/'), '', $diskUrl);
        }

        return $this->getBaseMediaDirectory();
    }
}
