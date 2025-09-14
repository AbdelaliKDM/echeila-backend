<?php

namespace Database\Seeders;

use App\Constants\Gender;
use App\Models\User;
use App\Support\Enum\UserRoles;
use App\Support\Enum\UserTypes;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $user = User::create([
      'firstname' => 'Super',
      'lastname' => 'Admin',
      'email' => 'super@admin.com',
      'phone' => '0666666666',
      'password' => bcrypt('123456789'),
      'type' => UserTypes::ADMIN,
      'gender' => Gender::MALE,
      'birthdate' => '1990-01-01',
      'full_address' => 'Algiers, Algeria',
      'status' => 'active',
      //        'city_id' => 1,
    ]);
    // public/storage/uploads/users/avatars/1.png
//      $filePath = public_path('assets/img/avatars/1.png');
//      \Storage::disk('public')->putFileAs('uploads/users/avatars', $filePath, $user->id .'.png');
//      $user->avatar = 'uploads/users/avatars/' . $user->id . '.png';
//      $user->save();
    $user->assignRole(UserRoles::SUPER_ADMIN);
  }
}
