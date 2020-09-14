<?php

namespace App\Nova\Actions;

use App\Helpers\App;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Facades\URL;
use Maatwebsite\Excel\Facades\Excel;
use Laravel\Nova\Http\Requests\ActionRequest;
use App\Library\Entities\Exports\UsersExporter;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;

class ExportUsers extends DownloadExcel
{
    /**
     * Constructor.
     * 
     * @return void
     */
    public function __construct()
    {
        if( ! App :: dev() )
        {
            URL::forceScheme('https');
        }
    }

    /**
     * Ids to be filtered.
     * 
     * @var array|null
     */
    private $ids;

     /**
     * @param ActionRequest $request
     * @param Action        $exportable
     *
     * @return array
     */
    public function handle(ActionRequest $request, Action $exportable): array
    {   
        $usersExportable = new UsersExporter;
        
        $this -> ids && $usersExportable -> setIDs($this -> ids);
        
        $response = Excel::download(
            $usersExportable,
            $this->getFilename(),
            $this->getWriterType()
        );
        
        return Action::download(
            $this->getDownloadUrl($response),
            $this->getFilename()
        );
    }

    /**
     * set ids to be filtered.
     * 
     * @var array|null
     */
    public function setIds($ids)
    {
        $this -> ids = $ids;
    }
}