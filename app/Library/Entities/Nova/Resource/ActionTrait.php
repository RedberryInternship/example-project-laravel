<?php

namespace App\Library\Entities\Nova\Resource;

use Illuminate\Http\Request;

trait ActionTrait
{
  /**
   * Get selected resources.
   * 
   * @return array|null
   */
  private function getSelectedResourceIds(): ?array
  {
      if(request() -> resources == 'all')
      {
          return [];
      }

      if(request() -> resources != null )
      {
          return array_map( function( $resourceId ) {
              return intval( $resourceId );
          }, explode( ',', request() -> all()[ 'resources' ]));
      }

      return null;
  }

  /**
   * Create custom exportable action.
   * 
   * @param string
   * @return mixed
   */
  private function createCustomExportableExcelAction( string $className )
  {
    $exportableUsers = new $className;
    $selectedRecords = $this -> getSelectedResourceIds();

    if($selectedRecords)
    {
        $exportableUsers->setIds($selectedRecords);
    }

    return $exportableUsers;
  }
}