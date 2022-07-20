<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;

use Carbon\Carbon;

use App\Models\RectifierItem;

use DB;
use DataTables;
use Auth;


class RectifierItemController extends Controller
{
    
    public function getAll(Request $request){

        $data = array(
            'code'=>$request->input('code'),
            'network_element_code'=>$request->input('network_element_code'),
            'item_type'=>$request->input('item_type'),
        );

        // return $data['item_type'];

        
        
    	try {
            $resp = auth()->userOrFail();

            $resp = RectifierItem::select(
                'rectifier_items.id',
                'rectifier_items.code',
                'rectifier_items.rectifier_code',
                'rectifier_items.item_code',
                'rectifier_items.item_type',
                'rectifier.manufacturer AS rectifier_manufacturer',
                'rectifier.index_no AS rectifier_index_no',
                'rectifier.model AS rectifier_model',
                'rectifier.maintainer AS rectifier_maintainer',
                'rectifier.status AS rectifier_status',
                'rectifier.date_installed AS rectifier_date_installed',
                'rectifier.date_accepted AS rectifier_date_accepted',
                'battery.manufacturer AS battery_manufacturer',
                'battery.index_no AS battery_index_no',
                'battery.model AS battery_model',
                'battery.maintainer AS battery_maintainer',
                'battery.status AS battery_status',
                'battery.date_installed AS battery_date_installed',
                'battery.date_accepted AS battery_date_accepted',
            )

            ->leftjoin('network_elements AS ne','ne.code','=','rectifier_items.item_code')
            ->leftjoin('rectifiers AS rectifier','rectifier.code','=','rectifier_items.rectifier_code')
            ->leftjoin('batteries AS battery','battery.code','=','rectifier_items.item_code')
            ;

            if ($data['item_type']=="Network Element"){
                $resp = $resp->where('rectifier_items.item_code', $data['network_element_code']);
            }

            if ($data['item_type']=="Battery"){
                $rectifierCodes = RectifierItem::select('rectifier_code')->where('item_code', $data['network_element_code'])->where('item_type','Network Element')->pluck('rectifier_code')->toArray();
                
                $resp = $resp->WHEREIN('rectifier_items.rectifier_code', $rectifierCodes)->WHERE('rectifier_items.item_type', 'Battery');
            }

            $dtables = DataTables::eloquent($resp)

            ->filterColumn('rectifier_manufacturer', function($query, $keyword) {
                $sql = "rectifier.manufacturer like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })

            ->filterColumn('rectifier_index_no', function($query, $keyword) {
                $sql = "rectifier.index_no like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })

            ->filterColumn('rectifier_model', function($query, $keyword) {
                $sql = "rectifier.model like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })

            ->filterColumn('rectifier_maintainer', function($query, $keyword) {
                $sql = "rectifier.maintainer like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })

            ->filterColumn('rectifier_status', function($query, $keyword) {
                $sql = "rectifier.status like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })

            ->filterColumn('rectifier_date_installed', function($query, $keyword) {
                $sql = "rectifier.date_installed like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })

            ->filterColumn('rectifier_date_accepted', function($query, $keyword) {
                $sql = "rectifier.date_accepted like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })

            ->filterColumn('battery_manufacturer', function($query, $keyword) {
                $sql = "battery.manufacturer like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })

            ->filterColumn('battery_index_no', function($query, $keyword) {
                $sql = "battery.index_no like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })

            ->filterColumn('battery_model', function($query, $keyword) {
                $sql = "battery.model like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })

            ->filterColumn('battery_maintainer', function($query, $keyword) {
                $sql = "battery.maintainer like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })

            ->filterColumn('battery_status', function($query, $keyword) {
                $sql = "battery.status like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })

            ->filterColumn('battery_date_installed', function($query, $keyword) {
                $sql = "battery.date_installed like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })

            ->filterColumn('battery_date_accepted', function($query, $keyword) {
                $sql = "battery.date_accepted like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ;
            
            return $dtables->toJson();

        } catch(JWTException $e) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function getAllSelect2(Request $request){ 

        $data = array(
            'id'=>$request->input('id'),
            'search'=>$request->input('search'),//select2 default
            'item_type'=>$request->input('item_type'),
            'rectifier_code'=>$request->input('rectifier_code'),
        );

        $training_code = null;
        
        $collection = RectifierItem::select(
            
            'rectifier_items.item_code AS id',
            DB::raw(
                "CASE 
                WHEN rectifier_items.item_type = 'Network Element'
                THEN ne.name
                WHEN rectifier_items.item_type = 'Battery'
                THEN battery.brand
                ELSE 'not found' END AS text"),
            'rectifier_items.item_type'

        )
        ->leftjoin('network_elements AS ne','ne.code','=','rectifier_items.item_code')
        ->leftjoin('batteries AS battery','battery.code','=','rectifier_items.item_code');
        
        // ->leftjoin('network_elements AS ne','ne.code','=','rectifiers.network_element_code');

        // if($data['search']){
        //     $collection = $collection->where('name', 'like', '%'.$data['search'].'%');
        // }

        if($data['item_type']){
            
            // if($data['item_type']=="Network Element"){
            //     $collection = $collection->leftjoin('network_elements AS ne','ne.code','=','rectifier_items.item_code');
            // }else{
            //     $collection = $collection->leftjoin('batteries AS battery','battery.code','=','rectifier_items.item_code');
            // }

            $collection = $collection->where('item_type', $data['item_type']);
        }

        if($data['rectifier_code']){
            $collection = $collection->where('rectifier_code', $data['rectifier_code']);
        }

        $query = $collection;

        $collection = $collection->get();

        return response()->json([
            'status'=>200,
            'results'=>$collection,
        ]);

    }

    public function findOne(Request $request){
    	$data = array(
            'id'=>$request->input('id')
        );
        
        $resp = RectifierItem::select('*');

        if ($data['id']){
            $resp = $resp->where('id', $data['id']);
        }

        $resp = $resp->get();

        return response()->json([
            'status'=>200,
            'data'=>$resp,
            'count'=>$resp->count(),
            'message'=>''
        ],200,[], JSON_NUMERIC_CHECK);
    }

    public function store(Request $request){

        $fields = $request->all();
        
        // $transaction = DB::transaction(function($field) use($fields){
        //     try{

                $resp = new Rectifier;
                // $resp->code             = "EXH-".(string) Str::uuid();
                $resp->code                     = $fields['code'];
                $resp->network_element_code     = $fields['network_element_code'];
                $resp->manufacturer             = $fields['manufacturer'];
                $resp->serial_no                = $fields['serial_no'];
                $resp->index_no                 = $fields['index_no'];
                $resp->model                    = $fields['model'];
                $resp->maintainer               = $fields['maintainer'];
                $resp->status                   = $fields['status'];
                $resp->date_installed           = $fields['date_installed'];
                $resp->date_accepted            = $fields['date_accepted'];
                $resp->rectifier_system_naming  = $fields['rectifier_system_naming'];
                $resp->type                     = $fields['type'];
                $resp->brand                    = $fields['brand'];
                $resp->no_of_existing_module    = $fields['no_of_existing_module'];
                $resp->no_of_slots              = $fields['no_of_slots'];
                $resp->capacity_per_module      = $fields['capacity_per_module'];
                $resp->full_capacity            = $fields['full_capacity'];
                $resp->dc_voltage               = $fields['dc_voltage'];
                $resp->total_actual_load        = $fields['total_actual_load'];
                $resp->percent_utilization      = $fields['percent_utilization'];
                $resp->external_alarm_activation = $fields['external_alarm_activation'];
                $resp->no_of_runs_and_cable_size = $fields['no_of_runs_and_cable_size'];
                $resp->tvss_brand_rating        = $fields['tvss_brand_rating'];
                $resp->rectifier_dc_breaker_brand = $fields['rectifier_dc_breaker_brand'];
                $resp->rectifier_battery_slot   = $fields['rectifier_battery_slot'];
                $resp->dcpdb_equipment_load_assignment = $fields['dcpdb_equipment_load_assignment'];
                $resp->remarks                  = $fields['remarks'];
                $resp->created_by               = Auth::user()->email;
                $resp->changed_by               = Auth::user()->email;
                $resp->save();




                return response()->json([
                    'status' => 200,
                    'data' => null,
                    'message' => 'Successfully saved.'
                ]);




        //     }
        //     catch (\Exception $e) 
        //     {
        //         return response()->json([
        //             'status' => 500,
        //             'data' => null,
        //             'message' => 'Error, please try again!'
        //         ]);
        //     }
        // });

        return $transaction;
    }

    public function update(Request $request){

        $fields = $request->all();

        // $transaction = DB::transaction(function($field) use($fields){
        // try{

            $resp = RectifierItem::where('id', $fields['id'])->first();
            $resp->code                     = $fields['code'];
            $resp->network_element_code     = $fields['network_element_code'];
            $resp->manufacturer             = $fields['manufacturer'];
            $resp->serial_no                = $fields['serial_no'];
            $resp->index_no                 = $fields['index_no'];
            $resp->model                    = $fields['model'];
            $resp->maintainer               = $fields['maintainer'];
            $resp->status                   = $fields['status'];
            $resp->date_installed           = $fields['date_installed'];
            $resp->date_accepted            = $fields['date_accepted'];
            $resp->rectifier_system_naming  = $fields['rectifier_system_naming'];
            $resp->type                     = $fields['type'];
            $resp->brand                    = $fields['brand'];
            $resp->no_of_existing_module    = $fields['no_of_existing_module'];
            $resp->no_of_slots              = $fields['no_of_slots'];
            $resp->capacity_per_module      = $fields['capacity_per_module'];
            $resp->full_capacity            = $fields['full_capacity'];
            $resp->dc_voltage               = $fields['dc_voltage'];
            $resp->total_actual_load        = $fields['total_actual_load'];
            $resp->percent_utilization      = $fields['percent_utilization'];
            $resp->external_alarm_activation = $fields['external_alarm_activation'];
            $resp->no_of_runs_and_cable_size = $fields['no_of_runs_and_cable_size'];
            $resp->tvss_brand_rating        = $fields['tvss_brand_rating'];
            $resp->rectifier_dc_breaker_brand = $fields['rectifier_dc_breaker_brand'];
            $resp->rectifier_battery_slot   = $fields['rectifier_battery_slot'];
            $resp->dcpdb_equipment_load_assignment = $fields['dcpdb_equipment_load_assignment'];
            $resp->remarks                  = $fields['remarks'];
            $resp->changed_by       = Auth::user()->email;
            $resp->save();

            return response()->json([
                'status' => 200,
                'data' => null,
                'message' => 'Successfully updated.'
            ]);

        //   }
        //   catch (\Exception $e) 
        //   {
        //     return response()->json([
        //       'status' => 500,
        //       'data' => null,
        //       'message' => 'Error, please try again!'
        //     ]);
        //   }
        // });

        return $transaction;
    }

    public function remove(Request $request){

	    $fields = $request->all();

	    // $transaction = DB::transaction(function($field) use($fields){
	    // try{

			RectifierItem::where('id', $fields['id'])->firstOrFail()->delete();

	        return response()->json([
	            'status' => 200,
	            'data' => 'null',
	            'message' => 'Successfully deleted.'
	        ]);

	    //   }
	    //   catch (\Exception $e) 
	    //   {
	    //       return response()->json([
	    //         'status' => 500,
	    //         'data' => 'null',
	    //         'message' => 'Error, please try again!'
	    //     ]);
	    //   }
	    // });

   	 	return $transaction;
  	}

}
