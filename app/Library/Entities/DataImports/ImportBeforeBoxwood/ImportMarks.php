<?php

namespace App\Library\Entities\DataImports\ImportBeforeBoxwood;

use Illuminate\Support\Facades\DB;

class ImportMarks
{
  /**
   * Insert marks.
   * 
   * @return void
   */
  public static function execute(): void
  {
    DB :: table( 'marks' ) -> insert(
      [
        ['name' => 'Audi'                   ],
        ['name' => 'BMW'                    ],
        ['name' => 'BMW Brilliance'         ],
        ['name' => 'Bolloré'                ],
        ['name' => 'BYD'                    ],
        ['name' => 'Chery'                  ],
        ['name' => 'Chevrolet'              ],
        ['name' => 'Citroën'                ],
        ['name' => 'COURB'                  ],
        ['name' => 'ElectraMeccanica'       ],
        ['name' => 'Fiat'                   ],
        ['name' => 'Ford'                   ],
        ['name' => 'Girfalco'               ],
        ['name' => 'Honda'                  ],
        ['name' => 'Hyundai'                ],
        ['name' => 'JAC Motors'             ],
        ['name' => 'Jaguar Land Rover'      ],
        ['name' => 'Kewet'                  ],
        ['name' => 'Kia'                    ],
        ['name' => 'Kyburz'                 ],
        ['name' => 'Lightning'              ],
        ['name' => 'Mahindra'               ],
        ['name' => 'Mercedes-Benz'          ],
        ['name' => 'Micro Mobility Systems' ],
        ['name' => 'Mitsubishi'             ],
        ['name' => 'Motores Limpios'        ],
        ['name' => 'MW Motors'              ],
        ['name' => 'NIO'                    ],
        ['name' => 'Nissan'                 ],
        ['name' => 'ECOmove'                ],
        ['name' => 'Peugeot'                ],
        ['name' => 'Rayttle'                ],
        ['name' => 'Renault'                ],
        ['name' => 'Smart'                  ],
        ['name' => 'Sono Motors'            ],
        ['name' => 'Stevens'                ],
        ['name' => 'Tesla'                  ],
        ['name' => 'Venturi'                ],
        ['name' => 'Volkswagen'             ],
      ]
    );
  }
}