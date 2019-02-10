<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Traits\Generators\GenerateRecipes;
use App\Traits\Generators\ModelFieldsArguments;

class ControllerGeneratorCommand extends Command
{
    use GenerateRecipes, ModelFieldsArguments;

    protected $signature = 'crud:controller';

    protected $description = 'Generates a CRUD controller class with requests for eloquent model.';

    public $recipes = [
        \App\Generators\ControllerRecipe::class,
        \App\Generators\RequestRecipe::class,
        \App\Generators\RoutesRecipe::class,
    ];

    public function handle()
    {
        $this->makeRecipes($this->recipes);
        $this->info("\nCRUD for {$this->model()} created successfully.");
    }
}
