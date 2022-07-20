<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;

use Carbon\Carbon;

use App\Models\NetworkElement;

use DB;
use DataTables;
use Auth;


class NetworkElementController extends Controller
{
    
    public function getAll(Request $request){

        $data = array(
            'code'=>$request->input('code'),
        );

    	try {
            $resp = auth()->userOrFail();

            $resp = NetworkElement::select('*');

            return DataTables::of($resp)->make(true);

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
        
        $collection = NetworkElement::select(
            'code as id',
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
            'data'=>$collection,
        ]);

    }

    public function findOne(Request $request){
    	$data = array(
            'id'=>$request->input('id')
        );
        
        $resp = NetworkElement::select('*');

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

                $resp = new NetworkElement;
                $resp->code             = "NET-".(string) Str::uuid();
                // $resp->code                          = $fields['code'];
                $resp->name                          = $fields['name'];
                $resp->type                          = $fields['type'];
                $resp->status                        = $fields['status'];
                $resp->vendor                        = $fields['vendor'];
                $resp->device_ip_address             = $fields['device_ip_address'];
                $resp->software_version              = $fields['software_version'];
                $resp->foc_assignment_uplink1        = $fields['foc_assignment_uplink1'];
                $resp->foc_assignment_uplink2        = $fields['foc_assignment_uplink2'];
                $resp->hon_assignment_uplink_port1   = $fields['hon_assignment_uplink_port1'];
                $resp->hon_assignment_uplink_port2   = $fields['hon_assignment_uplink_port2'];
                $resp->decom_date                    = $fields['decom_date'];
                $resp->new_node_name                 = $fields['new_node_name'];
                $resp->created_by                    = Auth::user()->email;
                $resp->changed_by                    = Auth::user()->email;
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

            $resp = NetworkElement::where('id', $fields['id'])->first();
            // $resp->code                          = $fields['code'];
            $resp->name                          = $fields['name'];
            $resp->type                          = $fields['type'];
            $resp->status                        = $fields['status'];
            $resp->vendor                        = $fields['vendor'];
            $resp->device_ip_address             = $fields['device_ip_address'];
            $resp->software_version              = $fields['software_version'];
            $resp->foc_assignment_uplink1        = $fields['foc_assignment_uplink1'];
            $resp->foc_assignment_uplink2        = $fields['foc_assignment_uplink2'];
            $resp->hon_assignment_uplink_port1   = $fields['hon_assignment_uplink_port1'];
            $resp->hon_assignment_uplink_port2   = $fields['hon_assignment_uplink_port2'];
            $resp->decom_date                    = $fields['decom_date'];
            $resp->new_node_name                 = $fields['new_node_name'];
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

			NetworkElement::where('id', $fields['id'])->firstOrFail()->delete();

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
