<?php

namespace App\Generators;

class ResourceRecipe extends ModelRecipe
{
    public function make()
    {
        $this->createDir('app/Http/Resources');

        return $this->createFileFromStub(
            'Resource',
            null,
            null,
            'app/Http/Resources/DataResource.php'
        );
    }
}
