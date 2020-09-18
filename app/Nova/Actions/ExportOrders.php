<?php

namespace App\Nova\Actions;

use App\Library\Entities\Exports\OrdersExporter;

class ExportOrders extends ExcelCustomExporter
{
    /**
     * Exportable class name.
     * 
     * @var string
     */
    protected $exportable = OrdersExporter :: class;
}