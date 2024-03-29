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
            'dc_panel_id'=>$request->input('dc_panel_id'),
        );

    	try {
            $resp = auth()->userOrFail();

            // $resp = DcPanelItem::select('*');

            $resp = DcPanelItem::select(
                'dc_panel_items.id',
                'dc_panel_items.code',
                'dc_panel_items.dc_panel_id',
                'dc_panel_items.breaker_no',
                'dc_panel_items.current',
                'dc_panel_items.ne_id',
                'ne.name AS ne_name',
            )
            ->leftjoin('network_elements as ne','ne.id','=','dc_panel_items.ne_id')
            ;

            if($data['dc_panel_id']){
                $resp = $resp->where('dc_panel_items.dc_panel_id', $data['dc_panel_id']);
            }


            $dtables = DataTables::eloquent($resp)

            ->filterColumn('ne_name', function($query, $keyword) {
                $sql = "ne.name like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            });
            
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
                $resp->code                 = "PDC-ITM-".(string) Str::uuid();
                $resp->dc_panel_id          = $fields['dc_panel_id'];
                $resp->ne_id                = $fields['network_element_id'];
                $resp->breaker_no           = $fields['breaker_no'];
                $resp->current              = $fields['current'];
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
            $resp->dc_panel_id          = $fields['dc_panel_id'];
            $resp->ne_id                = $fields['network_element_id'];
            $resp->breaker_no           = $fields['breaker_no'];
            $resp->current              = $fields['current'];
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
