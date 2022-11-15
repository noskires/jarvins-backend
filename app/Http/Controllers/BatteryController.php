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
                'batteries.index_no',
                'batteries.model',
                'batteries.maintainer',
                'batteries.status',
                'batteries.date_installed',
                'batteries.date_accepted',
                'batteries.capacity',
                'batteries.type',
                'batteries.brand',
                'batteries.individual_cell_voltage',
                'batteries.no_of_cells',
                'batteries.cell_status',
                'batteries.cable_size',
                'batteries.backup_time',
                'batteries.float_voltage_requirement',
                'batteries.remarks',
                'batteries.rectifier_id',
                DB::raw("CONCAT(rec_site.code,'RE',rectifier_manufacturer.code,LPAD(rectifier.index_no,3,0),'-',rec_site.name) AS rectifier_name"),
                DB::raw("CONCAT(rec_site.code,'BA',battery_manufacturer.code,LPAD(batteries.index_no,3,0)) AS battery_name"),
                'rectifier.site_id AS rec_site_id',
                'batteries.site_id AS battery_site_id',
                'battery_site.name AS site_name',
                'batteries.manufacturer_id AS battery_manufacturer_id',
                'battery_manufacturer.name AS battery_manufacturer_name'
            )
            ->leftjoin('rectifiers AS rectifier','rectifier.id','=','batteries.rectifier_id')
            ->leftjoin('lib_manufacturers AS battery_manufacturer','battery_manufacturer.id','=','batteries.manufacturer_id')
            ->leftjoin('lib_manufacturers AS rectifier_manufacturer','rectifier_manufacturer.id','=','rectifier.manufacturer_id')
            // ->leftjoin('sites AS site','site.id','=','rectifier.site_id')
            ->leftjoin('sites AS rec_site','rec_site.id','=','rectifier.site_id')
            ->leftjoin('sites AS battery_site','battery_site.id','=','batteries.site_id')
            ;

            $dtables = DataTables::eloquent($resp)

            ->filterColumn('site_name', function($query, $keyword) {
                $sql = "battery_site.name like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })

            ->filterColumn('rectifier_name', function($query, $keyword) {
                $sql = "CONCAT(rec_site.code,'RE',rectifier_manufacturer.code,LPAD(rectifier.index_no,3,0),'-',rec_site.name) like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })

            ->filterColumn('battery_manufacturer_name', function($query, $keyword) {
                $sql = "battery_manufacturer.name like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            
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
                $resp->site_id              = $fields['site_id'];
                $resp->manufacturer_id      = $fields['manufacturer'];
                $resp->rectifier_id         = $fields['rectifier'];
                $resp->index_no             = $fields['index_no'];
                $resp->model                = $fields['model'];
                $resp->maintainer           = $fields['maintainer'];
                $resp->status               = $fields['status'];
                $resp->date_installed       = $fields['date_installed'];
                $resp->date_accepted        = $fields['date_accepted'];
                $resp->capacity             = $fields['capacity'];
                $resp->type                 = $fields['type'];
                $resp->brand                = $fields['brand'];
                $resp->individual_cell_voltage = $fields['individual_cell_voltage'];
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
            $resp->site_id              = $fields['site_id'];
            $resp->manufacturer_id      = $fields['manufacturer'];
            $resp->rectifier_id         = $fields['rectifier'];
            $resp->index_no             = $fields['index_no'];
            $resp->model                = $fields['model'];
            $resp->maintainer           = $fields['maintainer'];
            $resp->status               = $fields['status'];
            $resp->date_installed       = $fields['date_installed'];
            $resp->date_accepted        = $fields['date_accepted'];
            $resp->capacity             = $fields['capacity'];
            $resp->type                 = $fields['type'];
            $resp->brand                = $fields['brand'];
            $resp->individual_cell_voltage = $fields['individual_cell_voltage'];
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
