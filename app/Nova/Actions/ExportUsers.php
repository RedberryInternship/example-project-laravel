<?php

namespace App\Nova\Actions;

use App\Library\Entities\Exports\UsersExporter;

class ExportUsers extends ExcelCustomExporter
{
    /**
     * Exportable class name.
     * 
     * @var string
     */
    protected $exportable = UsersExporter :: class;
}