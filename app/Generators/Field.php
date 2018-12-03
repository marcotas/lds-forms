<?php

namespace App\Generators;

class Field
{
    /** @var string */
    public $name;
    /** @var string */
    public $type;
    /** @var \Illuminate\Support\Collection */
    public $options;
    /** @var boolean */
    public $isBelongsTo = false;
    /** @var string */
    public $relationName = null;
    /** @var boolean */
    public $isSoftDeletes = false;

    protected $extension = 'pug';

    public function __construct(string $field)
    {
        list($this->name, $this->type, $options) = explode(':', $field) + [null, 'string', null];

        $this->options = collect($options ?? []);

        $this->checkBelongsTo();
        $this->checkSoftDeletes();
    }

    public function isSearchable() : bool
    {
        return collect(['string', 'text'])->contains($this->type);
    }

    public function label()
    {
        return title_case(str_replace('_', ' ', $this->name()));
    }

    public function inputType()
    {
        switch ($this->type) {
            case 'date':
            case 'dateTime':
                return 'date';
            case 'unsignedInteger':
            case 'tinyInteger':
            case 'bigInteger':
            case 'integer':
            case 'float':
            case 'decimal':
                return 'number';
            default:
                return 'text';
        }
    }

    public function getVueFormComponent()
    {
        if ($this->isSoftDeletes) {
            return null;
        }

        if ($this->isBelongsTo) {
            return $this->getPartial('input-select-2');
        }

        if ($this->type === 'text') {
            return $this->getPartial('input-textarea');
        }

        return $this->getPartial('input-' . $this->inputType());
    }

    protected function getPartial($partial)
    {
        $partial      = file_get_contents(resource_path("stubs/partials/$partial.{$this->extension}"));

        return str_replace(
            ['dummy_field', 'dummy_type', 'Dummy Label', 'dummy-url'],
            [$this->name, $this->inputType(), $this->getLabel(), $this->getUrlOf($this->name())],
            $partial
        );
    }

    public function getUrlOf($string): string
    {
        $string = snake_case($string);
        $string = str_replace('_', '-', $string);

        return str_plural($string);
    }

    protected function getLabel(): string
    {
        return title_case(str_replace('_', ' ', $this->name()));
    }

    public function needsType() : bool
    {
        return collect([
            'integer', 'date', 'dateTime', 'email', 'decimal', 'float',
        ])->contains($this->type);
    }

    public function getSchema(): string
    {
        if ($this->isSoftDeletes) {
            return "\$table->softDeletes();\n";
        }

        return "\$table->{$this->type}(\"{$this->name}\");";
    }

    protected function checkBelongsTo()
    {
        $this->isBelongsTo  = str_contains($this->type, ['belongsTo', 'belongs_to']);
        $this->type         = $this->isBelongsTo ? 'unsignedInteger' : $this->type;
        $this->relationName = $this->name;
        $this->name         = $this->isBelongsTo ? str_singular($this->name) . '_id' : $this->name;
    }

    protected function checkSoftDeletes()
    {
        $this->isSoftDeletes = $this->name === 'softDeletes';
        $this->type          = $this->isSoftDeletes ? 'dateTime' : $this->type;
    }

    public function name(): string
    {
        return $this->isBelongsTo ? $this->relationName : $this->name;
    }
}
