<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;

use Carbon\Carbon;

use App\Models\ServiceVehicle;
use App\Models\Employee;

use DB;
use DataTables;
use Auth;


class ServiceVehicleController extends Controller
{
    
    public function getAll(Request $request){

        $data = array(
            'employee_id'=>$request->input('employee_id'),
        );

    	try {
            $resp = auth()->userOrFail();

            $resp = ServiceVehicle::select(
                'e.employee_id as employee_id',
                DB::raw("CONCAT(rtrim(CONCAT(e.last_name,' ',COALESCE(e.affix,''))),', ', COALESCE(e.first_name,''),' ', COALESCE(e.middle_name,'')) as employee_name"),
                'service_vehicles.id',
                'service_vehicles.fleet_no',
                'service_vehicles.plate_no',
                'service_vehicles.manufacturer',
                'service_vehicles.make',
                'service_vehicles.production_year',
                'service_vehicles.sv_status',
                'service_vehicles.engine_serial_no',
                'service_vehicles.chassis_serial_no',
                'service_vehicles.body_serial_no',
                'service_vehicles.or_no',
                'service_vehicles.fleet_card_no',
                'service_vehicles.fuel_allocation',
            )
            ->leftjoin('employees as e','e.employee_id','=','service_vehicles.employee_id');

            if($data['employee_id']){
                $resp = $resp->where('service_vehicles.employee_id', $data['employee_id']);
            }

            $dtables = DataTables::eloquent($resp)
            ->filterColumn('employee_name', function($query, $keyword) {
                $sql = "CONCAT(rtrim(CONCAT(e.last_name,' ',COALESCE(e.affix,''))),', ', COALESCE(e.first_name,''),' ', COALESCE(e.middle_name,''))  like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            });

            return $dtables->toJson();

        } catch(JWTException $e) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function findOne(Request $request){
    	$data = array(
            'id'=>$request->input('id')
        );
        
        $resp = ServiceVehicle::select('*');

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

                $resp = new ServiceVehicle;
                $resp->code                 = "SV-".(string) Str::uuid();
                $resp->employee_id          = $fields['employee_id'];
                $resp->fleet_no             = $fields['fleet_no'];
                $resp->plate_no             = $fields['plate_no'];
                $resp->manufacturer         = $fields['manufacturer'];
                $resp->make                 = $fields['make'];
                $resp->production_year      = $fields['production_year'];
                $resp->sv_status            = $fields['sv_status'];
                $resp->engine_serial_no     = $fields['engine_serial_no'];
                $resp->chassis_serial_no    = $fields['chassis_serial_no'];
                $resp->body_serial_no       = $fields['body_serial_no'];
                $resp->or_no                = $fields['or_no'];
                $resp->fleet_card_no        = $fields['fleet_card_no'];
                $resp->fuel_allocation      = $fields['fuel_allocation'];
                $resp->created_by           = Auth::user()->email;
                $resp->changed_by           = Auth::user()->email;
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

            $resp = ServiceVehicle::where('id', $fields['id'])->first();
            $resp->employee_id          = $fields['employee_id'];
            $resp->fleet_no             = $fields['fleet_no'];
            $resp->plate_no             = $fields['plate_no'];
            $resp->manufacturer         = $fields['manufacturer'];
            $resp->make                 = $fields['make'];
            $resp->production_year      = $fields['production_year'];
            $resp->sv_status            = $fields['sv_status'];
            $resp->engine_serial_no     = $fields['engine_serial_no'];
            $resp->chassis_serial_no    = $fields['chassis_serial_no'];
            $resp->body_serial_no       = $fields['body_serial_no'];
            $resp->or_no                = $fields['or_no'];
            $resp->fleet_card_no        = $fields['fleet_card_no'];
            $resp->fuel_allocation      = $fields['fuel_allocation'];
            $resp->changed_by           = Auth::user()->email;
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

			ServiceVehicle::where('id', $fields['id'])->firstOrFail()->delete();

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
