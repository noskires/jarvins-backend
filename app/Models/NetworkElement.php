<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class NetworkElement extends Model implements Auditable{

    use \OwenIt\Auditing\Auditable;

    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = "network_elements";

    protected $casts =  [
        'id' => 'string',
    ];

    public function dcPanelItem(){
    	return $this->hasMany('App\Models\DcPanelItem', 'ne_id', 'id');
    }

    public function scopeDefaultFields($query){
    	
        $query->select(
            'network_elements.id',
            'network_elements.site_id',
            'network_elements.code',
            'network_elements.name',
            'network_elements.type',
            'network_elements.status',
            'network_elements.vendor',
            'network_elements.device_ip_address',
            'network_elements.software_version',
            'network_elements.foc_assignment_uplink1',
            'network_elements.hon_assignment_uplink_port1',
            'network_elements.foc_assignment_uplink2',
            'network_elements.hon_assignment_uplink_port2',
            'network_elements.decom_date',
            'network_elements.new_node_name',
            // 'dc_panel_items.breaker_no'
        )
        // ->leftJoin('dc_panel_items', function($join){
        //   $join->on('dc_panel_items.ne_id', '=', 'network_elements.id');
        // })
        ;
    }

    public function scopeWhereFields($query, $data){

        // if(Auth::user()->hasRole('User')){
        //     $query->where('employees.id', Auth::user()->id);
        // }
  
        if($data['ne_id']){
            $query->where('network_elements.id', $data['ne_id']);
        }
        
  
      }
}
