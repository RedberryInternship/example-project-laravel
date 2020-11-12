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
    $ids = $this -> getIds();
    $ids && $exportable -> setIDs($ids);
    
    Excel::store( $exportable, 'public/download.xlsx');

    $downloadUrl = config('app')['url'] . '/storage/download.xlsx';

    return Action::download( $downloadUrl, $this->getFilename());
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

  /**
   * Get ids to be filtered with.
   * 
   * @return array
   */
  public function getIds(): ?array
  {
    return $this -> query() -> pluck('id')->toArray();
  }
}