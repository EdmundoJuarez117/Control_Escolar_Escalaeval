<?php

namespace Database\Seeders;
use App\Models\C_escalaeval;
use Illuminate\Database\Seeder;

class escalaevalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        //Modalidades de la carrera
      $escalaeval = new C_escalaeval();
      $escalaeval->nombre = "Presencial Software";
      $escalaeval->calificacion_min = "7";
      $escalaeval->calificacion_max = "9";
      $escalaeval->save();

      $escalaeval = new C_escalaeval();
      $escalaeval->nombre = "Online Software";
      $escalaeval->calificacion_min = "6";
      $escalaeval->calificacion_max = "8";
      $escalaeval->save();
    }
}
