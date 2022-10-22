<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Manufacturer extends Model implements Auditable{

    use \OwenIt\Auditing\Auditable;

    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = "lib_manufacturers";
}
