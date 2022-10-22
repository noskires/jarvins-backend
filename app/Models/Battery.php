<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use DB;

class Battery extends Model implements Auditable{

    use \OwenIt\Auditing\Auditable;

    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = "batteries";

    protected $casts = [
        'site_id' => 'string'
    ];

    public function scopeDefaultFields($query){
    	
        $query->select(
            'batteries.id',
            'batteries.code',
            'batteries.manufacturer_id',
            'batteries.index_no',
            'batteries.model',
            'batteries.maintainer',
            'batteries.status',
            'batteries.date_installed',
            'batteries.date_accepted',
            'batteries.capacity',
            'batteries.type',
            'batteries.brand',
            'batteries.no_of_cells',
            'batteries.cell_status',
            'batteries.cable_size',
            'batteries.backup_time',
            'batteries.float_voltage_requirement',
            'batteries.remarks',
            'batteries.rectifier_id',
            'lib_manufacturers.name AS manufacturer_name',
            DB::raw("CONCAT(sites.code,'BA',lib_manufacturers.code,LPAD(batteries.index_no,3,0)) AS battery_name"),
        )
        ->leftJoin('lib_manufacturers', function($join){
          $join->on('lib_manufacturers.id', '=', 'batteries.manufacturer_id');
        })
        ->leftJoin('rectifiers', function($join){
            $join->on('rectifiers.id', '=', 'batteries.rectifier_id');
        })
        ->leftJoin('sites', function($join){
            $join->on('sites.id', '=', 'rectifiers.site_id');
        })
        ;
    }
}
