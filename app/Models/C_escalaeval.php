<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class C_escalaeval extends Model
{
    use HasFactory;
    protected $table = "c_escalaevals";

    const CREATED_AT = "fcreacion";
    const UPDATED_AT = "fmodificacion";

    protected $fillable = [
      "id",
      "nombre",
      "calificacion_min",
      "calificacion_max",
      "fcreacion",
      "fmodificacion"
    ];

    
    public static function getForPagination($ofset,$limit)
    {
      $countData = self::count();

      $data = self::
      select('id','nombre','calificacion_min','calificacion_max','fcreacion','fmodificacion')
      ->orderBy('nombre')
      ->offset($ofset)
      ->limit($limit)->get();
      return[
        'countData'=>$countData,
        'data'=>$data
      ];
    }
}
