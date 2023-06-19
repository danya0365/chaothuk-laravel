<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UniqueValue implements Rule
{
    protected $table;
    protected $column;

    public function __construct($table, $column)
    {
        $this->table = $table;
        $this->column = $column;
    }

    public function passes($attribute, $value)
    {
        return !DB::table($this->table)
            ->where($this->column, $value)
            ->exists();
    }

    public function message()
    {
        return trans('validation.unique_value');
    }
}
