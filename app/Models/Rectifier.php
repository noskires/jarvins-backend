<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use DB;

class Rectifier extends Model implements Auditable{

    use \OwenIt\Auditing\Auditable;

    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = "rectifiers";

    public function batteries(){
    	return $this->hasMany('App\Models\Battery', 'rectifier_id', 'id');
    }

    public function scopeDefaultFields($query){
    	
        $query->select(
            'rectifiers.id',
            'rectifiers.site_id',
            'rectifiers.code',
            'rectifiers.serial_no',
            'rectifiers.index_no',
            'rectifiers.model',
            'rectifiers.maintainer',
            'rectifiers.status',
            'rectifiers.date_installed',
            'rectifiers.date_accepted',
            'rectifiers.rectifier_system_naming',
            'rectifiers.type',
            'rectifiers.brand',
            'rectifiers.no_of_existing_module',
            'rectifiers.no_of_slots',
            'rectifiers.capacity_per_module',
            'rectifiers.full_capacity',
            'rectifiers.dc_voltage',
            'rectifiers.total_actual_load',
            'rectifiers.percent_utilization',
            'rectifiers.external_alarm_activation',
            'rectifiers.no_of_runs_and_cable_size',
            'rectifiers.tvss_brand_rating',
            'rectifiers.rectifier_dc_breaker_brand',
            'rectifiers.rectifier_battery_slot',
            'rectifiers.dcpdb_equipment_load_assignment',
            'rectifiers.remarks',
            'rectifiers.manufacturer_id',
            'rectifiers.site_id',
            'lib_manufacturers.name AS manufacturer_name',
            DB::raw("CONCAT(sites.code,'RE',lib_manufacturers.code,LPAD(rectifiers.index_no,3,0)) AS rectifier_name"),
        )
        ->leftJoin('lib_manufacturers', function($join){
          $join->on('lib_manufacturers.id', '=', 'rectifiers.manufacturer_id');
        })
        ->leftJoin('sites', function($join){
            $join->on('sites.id', '=', 'rectifiers.site_id');
        });
    }
}
