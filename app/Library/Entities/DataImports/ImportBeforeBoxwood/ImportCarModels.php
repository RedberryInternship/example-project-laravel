<?php

namespace App\Library\Entities\DataImports\ImportBeforeBoxwood;

use Illuminate\Support\Facades\DB;

class ImportCarModels
{
  /**
   * Insert car models.
   * 
   * @return void
   */
  public static function execute(): void
  {
    DB::table('car_models')->insert([
      [   
          'mark_id' => 1,
          'name'    => 'Audi e-tron ',
      ],
      [   
          'mark_id' => 2,
          'name'    => 'i3',
      ],
      [   
          'mark_id' => 3,
          'name'    => 'Zinoro 1E',
      ],
      [   
          'mark_id' => 4,
          'name'    => 'Bluecar',
      ],
      [   
          'mark_id' => 5,
          'name'    => 'e6',
      ],
      [   
          'mark_id' => 6,
          'name'    => 'QQ3 EV',
      ],
      [   
          'mark_id' => 7,
          'name'    => 'Bolt EV',
      ],
      [   
          'mark_id' => 7,
          'name'    => 'Spark EV',
      ],
      [   
          'mark_id' => 8,
          'name'    => 'C-Zero',
      ],
      [   
          'mark_id' => 9,
          'name'    => 'C-ZEN',
      ], 
      [   
          'mark_id' => 10,
          'name'    => 'Solo',
      ],
      [   
          'mark_id' => 11,
          'name'    => '500e',
      ],
      [   
          'mark_id' => 12,
          'name'    => 'Focus Electric',
      ],
      [   
          'mark_id' => 13,
          'name'    => 'Azkarra',
      ],
      [   
          'mark_id' => 14,
          'name'    => 'Fit EV',
      ],
      [   
          'mark_id' => 14,
          'name'    => 'Clarity Electric',
      ],             
       [   
          'mark_id' => 15,
          'name'    => 'Ioniq Electric',
      ],   
      [   
          'mark_id' => 15,
          'name'    => 'Kona Electric',
      ],   
      [   
          'mark_id' => 16,
          'name'    => 'JAC J3 EV',
      ],                      
      [   
          'mark_id' => 17,
          'name'    => 'Jaguar I-Pace',
      ],
      [   
          'mark_id' => 18,
          'name'    => 'Buddy',
      ],
      [   
          'mark_id' => 19,
          'name'    => 'Soul EV',
      ],
      [   
          'mark_id' => 19,
          'name'    => 'e-Niro',
      ],
      [   
          'mark_id' => 20,
          'name'    => 'Race',
      ],
      [   
          'mark_id' => 21,
          'name'    => 'Lightning GT',
      ],
      [   
          'mark_id' => 22,
          'name'    => 'e2o plus',
      ],
      [   
          'mark_id' => 22,
          'name'    => 'e-Verito',
      ],
      [   
          'mark_id' => 23,
          'name'    => 'B-Class Electric Drive',
      ],
      [   
          'mark_id' => 23,
          'name'    => 'EQC',
      ],
      [   
          'mark_id' => 24,
          'name'    => 'Microlino',
      ],
      [   
          'mark_id' => 25,
          'name'    => 'i-MiEV',
      ],
      [   
          'mark_id' => 26,
          'name'    => 'Zacua',
      ],
      [   
          'mark_id' => 27,
          'name'    => 'Luka EV',
      ],
      [   
          'mark_id' => 28,
          'name'    => 'ES8',
      ],
      [   
          'mark_id' => 29,
          'name'    => 'Leaf',
      ],
      [   
          'mark_id' => 30,
          'name'    => 'QBeak',
      ],
      [   
          'mark_id' => 31,
          'name'    => 'i0n',
      ],
      [   
          'mark_id' => 32,
          'name'    => 'E28',
      ],
      [   
          'mark_id' => 33,
          'name'    => 'Fluence Z.E.',
      ],
      [   
          'mark_id' => 33,
          'name'    => 'Zoe',
      ],
      [   
          'mark_id' => 33,
          'name'    => 'Twizy',
      ],
      [   
          'mark_id' => 34,
          'name'    => 'Smart electric drive',
      ],
      [   
          'mark_id' => 35,
          'name'    => 'Sion',
      ],
      [   
          'mark_id' => 36,
          'name'    => 'ZeCar',
      ],
      [   
          'mark_id' => 37,
          'name'    => 'Model S',
      ],
      [   
          'mark_id' => 37,
          'name'    => 'Model X',
      ],
      [   
          'mark_id' => 37,
          'name'    => 'Model 3',
      ],
      [   
          'mark_id' => 38,
          'name'    => 'Fétish',
      ],
      [   
          'mark_id' => 39,
          'name'    => 'e-Golf',
      ],
      [   
          'mark_id' => 39,
          'name'    => 'e-Up!',
      ]
    ]);
  }
}