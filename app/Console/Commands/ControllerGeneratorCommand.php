<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Traits\Generators\GenerateRecipes;

class ControllerGeneratorCommand extends Command
{
    use GenerateRecipes;

    protected $signature = 'crud:controller
        {name : Controller name}
        {model : Model name}';

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
