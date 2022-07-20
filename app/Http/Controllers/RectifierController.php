<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;

use Carbon\Carbon;

use App\Models\Rectifier;
use App\Models\RectifierItem;

use DB;
use DataTables;
use Auth;


class RectifierController extends Controller
{
    
    public function getAll(Request $request){

        $data = array(
            'code'=>$request->input('code'),
        );

    	try {
            $resp = auth()->userOrFail();

            // $resp = Rectifier::select('*');

            $resp = Rectifier::select(
                'rectifiers.id',
                'rectifiers.code',
                'rectifiers.manufacturer',
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
                
            );

            $dtables = DataTables::eloquent($resp);

            return $dtables->toJson();

            // return DataTables::of($resp)->make(true);

        } catch(JWTException $e) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function getAllSelect2(Request $request){ 

        $data = array(
            'id'=>$request->input('id'),
            'search'=>$request->input('search'),//select2 default
        );

        $training_code = null;
        
        $collection = Rectifier::select(
            'id',
            'name as text',
        );

        if($data['search']){
            $collection = $collection->where('name', 'like', '%'.$data['search'].'%');
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
        
        $resp = Rectifier::select('*');

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
                $resp->code                    = "REC-".(string) Str::uuid();
                // $resp->code                     = $fields['code'];
                // $resp->network_element_code     = $fields['network_element_code'];
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


                
                for($i = 0; $i < count($fields['network_element_code']); $i++) {
        
                    $respItem = new RectifierItem;
                    $respItem->code                     = "REC-ITM".(string) Str::uuid();
                    $respItem->rectifier_code           = $resp->code;
                    $respItem->item_code                = $fields['network_element_code'][$i];
                    $respItem->item_type                = "Network Element";
                    $respItem->created_by               = Auth::user()->email;
                    $respItem->changed_by               = Auth::user()->email;
                    $respItem->save();
                }

                for($i = 0; $i < count($fields['battery_code']); $i++) {
        
                    $respItem = new RectifierItem;
                    $respItem->code                     = "REC-ITM".(string) Str::uuid();
                    $respItem->rectifier_code           = $resp->code;
                    $respItem->item_code                = $fields['battery_code'][$i];
                    $respItem->item_type                = "Battery";
                    $respItem->created_by               = Auth::user()->email;
                    $respItem->changed_by               = Auth::user()->email;
                    $respItem->save();
                }


                

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

            $resp = Rectifier::where('id', $fields['id'])->first();
            // $resp->code                     = $fields['code'];
            // $resp->network_element_code     = $fields['network_element_code'];
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

            $rectifierItemsNe = RectifierItem::select('item_code')->where('rectifier_code', $fields['code'])->where('item_type','Network Element')->pluck('item_code')->toArray();
            $rectifierItemsBattery = RectifierItem::select('item_code')->where('rectifier_code', $fields['code'])->where('item_type','Battery')->pluck('item_code')->toArray();

            $ne_for_addition = array_values(array_diff($fields['network_element_code'], $rectifierItemsNe));
            $ne_for_deletion = array_values(array_diff($rectifierItemsNe, $fields['network_element_code']));

            $battery_for_addition = array_values(array_diff($fields['battery_code'], $rectifierItemsBattery));
            $battery_for_deletion = array_values(array_diff($rectifierItemsBattery, $fields['battery_code']));

            // if(count($ne_for_addition)>0){

                for($i = 0; $i < count($ne_for_addition); $i++) {

                    $respItem = new RectifierItem;
                    $respItem->code                     = "REC-ITM".(string) Str::uuid();
                    $respItem->rectifier_code           = $resp->code;
                    $respItem->item_code                = $ne_for_addition[$i];
                    $respItem->item_type                = "Network Element";
                    $respItem->created_by               = Auth::user()->email;
                    $respItem->changed_by               = Auth::user()->email;
                    $respItem->save();
                }
            // }

            // if(count($ne_for_deletion)>0){

                for($i = 0; $i < count($ne_for_deletion); $i++) {
                    RectifierItem::where('rectifier_code', $fields['code'])->where('item_code', $ne_for_deletion[$i])->firstOrFail()->delete();
                }
            // }
            
            for($i = 0; $i < count($battery_for_addition); $i++) {
        
                $respItem = new RectifierItem;
                $respItem->code                     = "REC-ITM".(string) Str::uuid();
                $respItem->rectifier_code           = $resp->code;
                $respItem->item_code                = $battery_for_addition[$i];
                $respItem->item_type                = "Battery";
                $respItem->created_by               = Auth::user()->email;
                $respItem->changed_by               = Auth::user()->email;
                $respItem->save();
            }

            for($i = 0; $i < count($battery_for_deletion); $i++) {
                RectifierItem::where('rectifier_code', $fields['code'])->where('item_code', $battery_for_deletion[$i])->firstOrFail()->delete();
            }

// $array1 = array("a" => "sky", "star", "moon", "cloud", "moon");
// $array2 = array("b" => "sky", "sun", "moon");
 
// // Comparing the values
// $result = array_diff($array2, $array1);
// print_r($result);

// // result 1
// // $result = array_diff($array1, $array2);
// // Array
// // (
// // [0] => star
// // [2] => cloud
// // )

// // result 2
// // $result = array_diff($array2, $array1);
// // Array
// // (
// // [0] => sun
// // )

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

			Rectifier::where('id', $fields['id'])->firstOrFail()->delete();

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
