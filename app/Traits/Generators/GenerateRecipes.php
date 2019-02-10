<?php

namespace App\Traits\Generators;

trait GenerateRecipes
{
    public function makeRecipes(array $recipes)
    {
        collect($recipes)->each(function ($recipeClass) {
            $recipeClassName = collect(explode('\\', $recipeClass))->last();
            $recipeName = str_replace('-', ' ', kebab_case(str_replace('Recipe', '', $recipeClassName)));
            $recipe = new $recipeClass($this->model(), $this->argument('fields'), $this);
            $this->comment("Making $recipeName...");
            $result = $recipe->make();

            if (is_iterable($result)) {
                foreach ($result as $filename) {
                    $this->info($filename);
                }

                return;
            }
            $this->info($result);
        });
    }

    public function model()
    {
        return studly_case(str_singular($this->argument('model')));
    }
}
