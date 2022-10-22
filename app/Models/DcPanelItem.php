<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class DcPanelItem extends Model implements Auditable{

    use \OwenIt\Auditing\Auditable;

    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = "dc_panel_items";
    
    // public function neDcPanelItem(){
    // 	return $this->hasOne('App\Models\NetworkElement', 'id', 'ne_id');
    // }

    public function dcPanel(){
    	return $this->hasOne('App\Models\DcPanel', 'id', 'dc_panel_id');
    }
    
    public function scopeDefaultFields($query){
    	
        $query->select(
            'id',
            'dc_panel_id',
            'ne_id',
            'breaker_no',
            'current',
        )
        ;
    }
}