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

            // $resp = NetworkElement::select('*');

            // return DataTables::of($resp)->make(true);

            $resp = NetworkElement::select(
                'network_elements.id',
                'network_elements.code',
                'network_elements.name',
                'network_elements.site_id',
                'network_elements.type',
                'network_elements.status',
                'network_elements.vendor',
                'network_elements.device_ip_address',
                'network_elements.software_version',
                'network_elements.foc_assignment_uplink1',
                'network_elements.foc_assignment_cid1',
                'network_elements.hon_assignment_uplink_port1',
                'network_elements.homing_node1',
                'network_elements.foc_assignment_uplink2',
                'network_elements.foc_assignment_cid2',
                'network_elements.hon_assignment_uplink_port2',
                'network_elements.homing_node2',
                'network_elements.decom_date',
                'network_elements.new_node_name',
                'site.name AS site_name',
            )->leftjoin('sites AS site','site.id','=','network_elements.site_id')
            ;

            $dtables = DataTables::eloquent($resp);

            return $dtables->toJson();

        } catch(JWTException $e) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function getAll2(Request $request){

        $data = array(
            'id'=>$request->input('id'),
            'ne_id'=>$request->input('ne_id'),
            'code'=>$request->input('code'),
        );

    	try {

            $resp = auth()->userOrFail();

            $collection['network_element'] = NetworkElement::defaultFields()->whereFields($data)
            ->with(['dcPanelItem'=>function($query) use ($data){
                $query->defaultFields()->with(['dcPanel'=>function($query) use ($data){
                    $query->defaultFields()->with(['rectifier'=>function($query) use ($data){
                        $query->defaultFields()->with(['batteries'=>function($query) use ($data){
                            $query->defaultFields();
                        }]);
                    }]);
                }]);
            }])
            ->first();

            return $collection;

        } catch(JWTException $e) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function getAllSelect2(Request $request){ 

        $data = array(
            'id'=>$request->input('id'),
            'dc_panel_id'=>$request->input('dc_panel_id'),
            'search'=>$request->input('search'),//select2 default
        );

        // $training_code = null;
        
        $collection = NetworkElement::select(
            'id as id',
            'name as text',
        );

        if($data['dc_panel_id']){
            $collection = $collection->where(DB::raw(
            "CASE
            WHEN
                (SELECT COALESCE(COUNT(dc_panel_id), 0) 
                FROM dc_panel_items AS dpi 
                WHERE dpi.ne_id = network_elements.id) > 1
            THEN 'YES' 
            WHEN 
                (SELECT COALESCE(COUNT(dc_panel_id), 0) 
                FROM dc_panel_items AS dpi 
                WHERE dpi.ne_id = network_elements.id AND dpi.dc_panel_id = '".$data['dc_panel_id']."') > 0 
            THEN 'YES' 
            ELSE 'NO' END"), "NO");
        }

        if($data['id']){
            $collection = $collection->whereNotIn('network_elements.id', 'like', '%'.$data['id'].'%');
        }

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
                // $resp->code             = "NET-".(string) Str::uuid();
                $resp->code                          = $fields['code'];
                $resp->site_id                       = $fields['site_id'];
                $resp->name                          = $fields['name'];
                $resp->type                          = $fields['type'];
                $resp->status                        = $fields['status'];
                $resp->vendor                        = $fields['vendor'];
                $resp->device_ip_address             = $fields['device_ip_address'];
                $resp->software_version              = $fields['software_version'];
                $resp->foc_assignment_uplink1        = $fields['foc_assignment_uplink1'];
                $resp->foc_assignment_cid1           = $fields['foc_assignment_cid1'];
                $resp->foc_assignment_uplink2        = $fields['foc_assignment_uplink2'];
                $resp->foc_assignment_cid2           = $fields['foc_assignment_cid2'];
                $resp->hon_assignment_uplink_port1   = $fields['hon_assignment_uplink_port1'];
                $resp->homing_node1                  = $fields['homing_node1'];
                $resp->hon_assignment_uplink_port2   = $fields['hon_assignment_uplink_port2'];
                $resp->homing_node2                  = $fields['homing_node2'];
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
            $resp->code                          = $fields['code'];
            $resp->site_id                       = $fields['site_id'];
            $resp->name                          = $fields['name'];
            $resp->type                          = $fields['type'];
            $resp->status                        = $fields['status'];
            $resp->vendor                        = $fields['vendor'];
            $resp->device_ip_address             = $fields['device_ip_address'];
            $resp->software_version              = $fields['software_version'];
            $resp->foc_assignment_uplink1        = $fields['foc_assignment_uplink1'];
            $resp->foc_assignment_cid1           = $fields['foc_assignment_cid1'];
            $resp->foc_assignment_uplink2        = $fields['foc_assignment_uplink2'];
            $resp->foc_assignment_cid2           = $fields['foc_assignment_cid2'];
            $resp->hon_assignment_uplink_port1   = $fields['hon_assignment_uplink_port1'];
            $resp->homing_node1                  = $fields['homing_node1'];
            $resp->hon_assignment_uplink_port2   = $fields['hon_assignment_uplink_port2'];
            $resp->homing_node2                  = $fields['homing_node2'];
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
