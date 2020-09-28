<?php

namespace App\Nova\Actions;

use Laravel\Nova\Actions\Action;
use Maatwebsite\Excel\Facades\Excel;
use Laravel\Nova\Http\Requests\ActionRequest;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;

class ExcelCustomExporter extends DownloadExcel
{
  /**
   * Exportable class name.
   * 
   * @var string
   */
  protected $exportable;
  
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
    if( ! $this -> exportable )
    {
      throw new \Exception( 'Exportable class should be set.', 500 );
    }

    $exportable = new $this -> exportable;

    $this -> ids && $exportable -> setIDs($this -> ids);
    
    Excel::store( $exportable, 'public/download.xlsx');

    $downloadUrl = config('app')['url'] . '/storage/download.xlsx';

    return Action::download( $downloadUrl, $this->getFilename());
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

  /**
   * Set exportable instance.
   * 
   * @param $exportable
   * @return void
   */
  public function setExportable( $exportable )
  {
    $this -> exportable = $exportable;
  }

    
}