<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;

use Carbon\Carbon;

use App\Models\DcPanel;

use DB;
use DataTables;
use Auth;


class DcPanelController extends Controller
{
    
    public function getAll(Request $request){

        $data = array(
            'code'=>$request->input('code'),
        );

    	try {
            $resp = auth()->userOrFail();

            // $resp = DcPanel::select('*');

            $resp = DcPanel::select(
                'dc_panels.id',
                'dc_panels.code',
                'dc_panels.index_no',
                'dc_panels.model',
                'dc_panels.maintainer',
                'dc_panels.status',
                'dc_panels.date_installed',
                'dc_panels.date_accepted',
                'dc_panels.fuse_breaker_number',
                'dc_panels.fuse_breaker_rating',
                'dc_panels.feed_source',
                'dc_panels.no_of_runs_and_cable_size',
                'dc_panels.source_voltage',
                'dc_panels.source_electric_current',
                'dc_panels.status_of_breakers',
                'dc_panels.date_accepted',
                'dc_panels.remarks',
                DB::raw("CONCAT(rec_site.code,'DC',dc_panel_manufacturer.code,LPAD(dc_panels.index_no,3,0)) AS dc_panel_name"),
                'dc_panels.rectifier_id',
                DB::raw("CONCAT(rec_site.code,'RE',rec_manufacturer.code,LPAD(rectifier.index_no,3,0),'-',rec_site.name) AS rectifier_name"),
                // 'rectifier.manufacturer_id as rectifier_id',
                // 'rec_manufacturer.name AS rectifier_manufacturer',
                'dc_panels.manufacturer_id',
                'dc_panel_manufacturer.name AS manufacturer_name',
                'dc_panels.site_id AS dc_panel_site_id',
                'rectifier.site_id AS rectifier_site_id',
                // 'rec_site.name as site_name',
                'dc_panel_site.name as site_name',
            )
            ->leftjoin('rectifiers as rectifier','rectifier.id','=','dc_panels.rectifier_id')
            ->leftjoin('lib_manufacturers AS rec_manufacturer','rec_manufacturer.id','=','rectifier.manufacturer_id')
            ->leftjoin('lib_manufacturers AS dc_panel_manufacturer','dc_panel_manufacturer.id','=','dc_panels.manufacturer_id')
            ->leftjoin('sites AS rec_site','rec_site.id','=','rectifier.site_id')
            ->leftjoin('sites AS dc_panel_site','dc_panel_site.id','=','dc_panels.site_id')
            ;


            $dtables = DataTables::eloquent($resp)

            // ->filterColumn('network_element_name', function($query, $keyword) {
            //     $sql = "ne.name like ?";
            //     $query->whereRaw($sql, ["%{$keyword}%"]);
            // });
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
        );

        $training_code = null;
        
        $collection = DcPanel::select(
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
        
        $resp = DcPanel::select('*');

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

                $resp = new DcPanel;
                $resp->code                 = "PDC-".(string) Str::uuid();
                $resp->site_id              = $fields['site_id'];
                $resp->rectifier_id         = $fields['rectifier'];
                $resp->manufacturer_id      = $fields['manufacturer'];
                $resp->index_no             = $fields['index_no'];
                $resp->model                = $fields['model'];
                $resp->maintainer           = $fields['maintainer'];
                $resp->status               = $fields['status'];
                $resp->date_installed       = $fields['date_installed'];
                $resp->date_accepted        = $fields['date_accepted'];
                $resp->fuse_breaker_number  = $fields['fuse_breaker_number'];
                $resp->fuse_breaker_rating  = $fields['fuse_breaker_rating'];
                $resp->feed_source          = $fields['feed_source'];
                $resp->no_of_runs_and_cable_size = $fields['no_of_runs_and_cable_size'];
                $resp->source_voltage       = $fields['source_voltage'];
                $resp->source_electric_current = $fields['source_electric_current'];
                $resp->status_of_breakers   = $fields['status_of_breakers'];
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

            $resp = DcPanel::where('id', $fields['id'])->first();
            $resp->site_id              = $fields['site_id'];
            $resp->rectifier_id         = $fields['rectifier'];
            $resp->manufacturer_id      = $fields['manufacturer'];
            $resp->index_no             = $fields['index_no'];
            $resp->model                = $fields['model'];
            $resp->maintainer           = $fields['maintainer'];
            $resp->status               = $fields['status'];
            $resp->date_installed       = $fields['date_installed'];
            $resp->date_accepted        = $fields['date_accepted'];
            $resp->fuse_breaker_number  = $fields['fuse_breaker_number'];
            $resp->fuse_breaker_rating  = $fields['fuse_breaker_rating'];
            $resp->feed_source          = $fields['feed_source'];
            $resp->no_of_runs_and_cable_size = $fields['no_of_runs_and_cable_size'];
            $resp->source_voltage       = $fields['source_voltage'];
            $resp->source_electric_current = $fields['source_electric_current'];
            $resp->status_of_breakers   = $fields['status_of_breakers'];
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

			DcPanel::where('id', $fields['id'])->firstOrFail()->delete();

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
