<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;

use Carbon\Carbon;

use App\Models\DcPanelItem;

use DB;
use DataTables;
use Auth;


class DcPanelItemController extends Controller
{
    
    public function getAll(Request $request){

        $data = array(
            'code'=>$request->input('code'),
        );

    	try {
            $resp = auth()->userOrFail();

            // $resp = DcPanelItem::select('*');

            $resp = DcPanelItem::select(
                'dc_panel_items.id',
                'dc_panel_items.code',
                'dc_panel_items.dc_panel_code',
                'dc_panel_items.breaker_name',
                'dc_panel_items.breaker_index_no',
            )
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
        
        $collection = DcPanelItem::select(
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
        
        $resp = DcPanelItem::select('*');

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

                $resp = new DcPanelItem;
                $resp->code                 = "PDC-".(string) Str::uuid();
                $resp->manufacturer         = $fields['manufacturer'];
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

            $resp = DcPanelItem::where('id', $fields['id'])->first();
            $resp->manufacturer         = $fields['manufacturer'];
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

			DcPanelItem::where('id', $fields['id'])->firstOrFail()->delete();

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
