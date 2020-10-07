<?php

namespace App\Nova\Actions;

use App\Library\Entities\Exports\ChargerOrdersExporter;

class ExportChargerOrders extends ExcelCustomExporter
{
    /**
     * Exportable class name.
     * 
     * @var string
     */
    protected $exportable = ChargerOrdersExporter :: class;

    /**
     * Set displayable name.
     * 
     * @return string
     */
    public function name()
    {
        return 'Charger Orders - Download Excel';
    }
}
