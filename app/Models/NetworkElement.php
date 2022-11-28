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

    protected $fillable = [
        'code',
        'name',
        'site_id',
        'type_id',
        'status',
        'vendor_id',
        'device_ip_address',
        'software_version',
        'foc_assignment_uplink1',
        'foc_assignment_cid1',
        'hon_assignment_uplink_port1',
        'homing_node1',
        'foc_assignment_uplink2',
        'foc_assignment_cid2',
        'hon_assignment_uplink_port2',
        'homing_node2',
        'decom_date',
        'new_node_name',
        'created_by',
        'changed_by',
    ];

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
            'network_elements.type_id',
            'network_elements.status',
            'network_elements.vendor_id',
            'network_elements.device_ip_address',
            'network_elements.software_version',
            'network_elements.foc_assignment_uplink1',
            'network_elements.foc_assignment_cid1',
            'network_elements.hon_assignment_uplink_port1',
            'network_elements.homing_node1',
            'network_elements.foc_assignment_uplink2',
            'network_elements.foc_assignment_cid2',
            'network_elements.hon_assignment_uplink_port2',
            'network_elements.homing_node2',
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
