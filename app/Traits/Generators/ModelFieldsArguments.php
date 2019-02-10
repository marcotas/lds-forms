<?php

namespace App\Traits\Generators;

trait ModelFieldsArguments
{
    protected $signatureOptions = '
        {model : Model name (singular). Example: User}
        {--y|yes : Yes to all interactive questions.}
        {fields* : Fields with its type. Example name:string active:boolean}';

    public function __construct()
    {
        $this->signature = $this->signature . ' ' . $this->signatureOptions;
        parent::__construct();
    }
}
