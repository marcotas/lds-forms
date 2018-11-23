<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CrudGeneratorCommand extends Command
{
    protected $signature = 'generate
        {model : Model name (singular). Example: User}
        {fields* : Fields with its type. Example name:string active:boolean}';

    protected $description = 'CRUD generator for eloquent models.';

    public function handle()
    {
        $model  = studly_case(str_singular($this->argument('model')));
        $fields = $this->argument('fields');
        $this->makeMigration($model, $fields);
        $this->makeModel($model, $fields);
        $this->makeController($model);
        $this->makeRequest($model, $fields);
        $this->makeRoutes($model);

        $this->info("\nCRUD for $model created successfully.");
    }

    protected function makeMigration($model, $fields)
    {
        $this->comment('Creating migration');
        $tableName         = $this->getTableName($model);
        $migrationTemplate = str_replace(
            [
                '{{modelNamePlural}}',
                '{{modelNamePluralLowerCase}}',
                '{{schema}}',
            ],
            [
                str_plural($model),
                $tableName,
                $this->getSchema($fields),
            ],
            $this->getStub('migration')
        );

        $timestamp = now()->format('Y_m_d_His');
        $filename  = '{{timestamp}}_create_{{tableName}}_table.php';
        $filename  = str_replace(
            [
                '{{timestamp}}',
                '{{tableName}}'
            ],
            [
                $timestamp,
                $tableName
            ],
            $filename
        );
        file_put_contents(base_path("/database/migrations/{$filename}"), $migrationTemplate);
        $this->info("Created migration $filename.");
    }

    protected function makeModel($model, $fields)
    {
        $this->comment('Creating model');
        $modelTemplate = str_replace(
            ['{{modelName}}', '{{fillable}}'],
            [$model, $this->getFillable($fields)],
            $this->getStub('Model')
        );

        file_put_contents(app_path("/Models/{$model}.php"), $modelTemplate);
        $this->info("Created model App\Models\\$model.");
    }

    protected function makeController($model)
    {
        $this->comment('Creating controller');
        $controllerTemplate = str_replace(
            [
                '{{modelName}}',
                '{{variableFormPlural}}',
                '{{variableFormSingular}}'
            ],
            [
                $model,
                $this->getVariableFormPlural($model),
                $this->getVariableFormSingular($model),
            ],
            $this->getStub('Controller')
        );

        file_put_contents(app_path("/Http/Controllers/{$model}Controller.php"), $controllerTemplate);

        $this->info("Created controller App\Http\Controllers\\{$model}Controller.");
    }

    protected function makeRequest($model, $fields)
    {
        $this->comment('Creating request');
        $requestTemplate = str_replace(
            ['{{modelName}}', '{{rules}}'],
            [$model, $this->getRequestRules($fields)],
            $this->getStub('Request')
        );

        file_put_contents(app_path("/Http/Requests/{$model}Request.php"), $requestTemplate);
        $this->info("Created request App\Http\Requests\\{$model}Request.");
    }

    private function makeRoutes($model)
    {
        $this->comment('Creating routes');
        $url = $this->getUrlForm($model);
        File::append(
            base_path('routes/web.php'),
            "Route::resource('$url', '{$model}Controller');\n"
        );
        $this->info("Created resource routes in '/$url'.");
    }

    protected function getSchema($fields)
    {
        return collect($fields)->map(function ($field) {
            list($field, $type) = explode(':', $field) + [null, 'string'];

            return "\n" . str_repeat(' ', 12) . "\$table->$type('$field');";
        })->implode('');
    }

    private function getFillable($fields)
    {
        return collect($fields)->map(function ($field) {
            list($field) = explode(':', $field);

            return "\n" . str_repeat(' ', 8) . "'$field',";
        })->implode('');
    }

    private function getVariableFormSingular($model)
    {
        return lcfirst($model);
    }

    private function getVariableFormPlural($model)
    {
        return lcfirst($model);
    }

    private function getTableName($model)
    {
        return str_plural(snake_case($model));
    }

    private function getRequestRules($fields)
    {
        return collect($fields)->map(function ($field) {
            list($field) = explode(':', $field);

            return "\n" . str_repeat(' ', 12) . "'$field' => 'required',";
        })->implode('');
    }

    private function getUrlForm($model)
    {
        return str_plural(kebab_case($model));
    }

    protected function getStub($type)
    {
        return file_get_contents(resource_path("stubs/$type.stub"));
    }
}
