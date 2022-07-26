<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Audit extends Model implements Auditable{

    use \OwenIt\Auditing\Auditable;

    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = "audits";

    // protected $casts = [
    //     'old_values'   => 'array',
    //     'new_values'   => 'array',
    //     'auditable_id' => 'integer',
    // ];

}
