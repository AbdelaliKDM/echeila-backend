<?php

namespace Database\Seeders;

use App\Models\Wilaya;
use Illuminate\Database\Seeder;

class WilayaSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $wilayas = [
      [
        'name' => 'Wilaya A',
        'latitude' => 36.7538,
        'longitude' => 3.0588,
      ],
      [
        'name' => 'Wilaya B',
        'latitude' => 35.6969,
        'longitude' => -0.6331,
      ],
      [
        'name' => 'Wilaya C',
        'latitude' => 36.3650,
        'longitude' => 6.6147,
      ],
      [
        'name' => 'Wilaya D',
        'latitude' => 36.9000,
        'longitude' => 7.7667,
      ],
      [
        'name' => 'Wilaya E',
        'latitude' => 36.1833,
        'longitude' => 5.4167,
      ],
    ];

    foreach ($wilayas as $wilaya) {
      Wilaya::create($wilaya);
    }
  }
}