<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\InteractsWithQueue;
use Maatwebsite\Excel\Facades\Excel;
use App\Library\Entities\Exports\UsersStatisticsExporter;

class ExportUsersStatistics extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $selectedYear = $fields -> year;
        
        if($selectedYear)
        {
            $exportable = new UsersStatisticsExporter;
            $exportable -> setYear($selectedYear);

            Excel::store( $exportable, 'public/download.xlsx');
            $downloadUrl = config('app')['url'] . '/storage/download.xlsx';

            return Action::download( $downloadUrl, 'users-statistics');
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        $currentYear    = now()->year;
        $years          = [];

        for($i=2020; $i <= $currentYear; $i++)
        {
            $years [$i]= $i;
        }

        return [
            Select::make('Year') -> options($years)->required(),
        ];
    }

    /**
     * The text to be used for the action's confirm button.
     *
     * @var string
     */
    public $confirmButtonText = 'Export';

    /**
     * The text to be used for the action's confirmation text.
     *
     * @var string
     */
    public $confirmText = 'Export users statistics...';
}
