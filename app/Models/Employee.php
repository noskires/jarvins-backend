<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

use Altek\Accountant\Contracts\Recordable;

class Employee extends Model implements Auditable{

    use \OwenIt\Auditing\Auditable;
    // use \Altek\Accountant\Recordable;

    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = "employees";
}
