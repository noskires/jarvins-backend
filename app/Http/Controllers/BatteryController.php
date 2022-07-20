<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;

use Carbon\Carbon;

use App\Models\Battery;

use DB;
use DataTables;
use Auth;


class BatteryController extends Controller
{
    
    public function getAll(Request $request){

        $data = array(
            'code'=>$request->input('code'),
        );

    	try {
            $resp = auth()->userOrFail();

            // $resp = Battery::select('*');

            $resp = Battery::select(
                'batteries.id',
                'batteries.code',
                'batteries.manufacturer',
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
                
            )
            ;

            // ->leftjoin('network_elements AS ne','ne.code','=','batteries.network_element_code');

            $dtables = DataTables::eloquent($resp)

            ->filterColumn('network_element_name', function($query, $keyword) {
                $sql = "ne.name like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            });

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
        
        $collection = Battery::select(
            'code AS id',
            'brand AS text',
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
        
        $resp = Battery::select('*');

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

                $resp = new Battery;
                $resp->code                 = "BAT-".(string) Str::uuid();
                $resp->manufacturer         = $fields['manufacturer'];
                $resp->index_no             = $fields['index_no'];
                $resp->model                = $fields['model'];
                $resp->maintainer           = $fields['maintainer'];
                $resp->status               = $fields['status'];
                $resp->date_installed       = $fields['date_installed'];
                $resp->date_accepted        = $fields['date_accepted'];
                $resp->capacity             = $fields['capacity'];
                $resp->type                 = $fields['type'];
                $resp->brand                = $fields['brand'];
                $resp->no_of_cells          = $fields['no_of_cells'];
                $resp->cell_status          = $fields['cell_status'];
                $resp->cable_size           = $fields['cable_size'];
                $resp->backup_time          = $fields['backup_time'];
                $resp->float_voltage_requirement = $fields['float_voltage_requirement'];
                $resp->remarks              = $fields['remarks'];
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

            $resp = Battery::where('id', $fields['id'])->first();
            $resp->manufacturer         = $fields['manufacturer'];
            $resp->index_no             = $fields['index_no'];
            $resp->model                = $fields['model'];
            $resp->maintainer           = $fields['maintainer'];
            $resp->status               = $fields['status'];
            $resp->date_installed       = $fields['date_installed'];
            $resp->date_accepted        = $fields['date_accepted'];
            $resp->capacity             = $fields['capacity'];
            $resp->type                 = $fields['type'];
            $resp->brand                = $fields['brand'];
            $resp->no_of_cells          = $fields['no_of_cells'];
            $resp->cell_status          = $fields['cell_status'];
            $resp->cable_size           = $fields['cable_size'];
            $resp->backup_time          = $fields['backup_time'];
            $resp->float_voltage_requirement = $fields['float_voltage_requirement'];
            $resp->remarks              = $fields['remarks'];
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

			Battery::where('id', $fields['id'])->firstOrFail()->delete();

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
