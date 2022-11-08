<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;

use Carbon\Carbon;

use App\Models\Permission;

use DB;
use DataTables;
use Auth;


class PermissionController extends Controller
{
    
    public function getAll(Request $request){

        $data = array(
            'code'=>$request->input('code'),
        );

    	try {
            $resp = auth()->userOrFail();

            $resp = Permission::select('*');

            return DataTables::of($resp)->make(true);

        } catch(JWTException $e) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function getAllSelect2(Request $request){ 

        $data = array(
            'id'=>$request->input('id'),
            'user_id'=>$request->input('user_id'),
            'search'=>$request->input('search'),//select2 default
        );

        $training_code = null;
        
        $collection = Permission::select(
            'id',
            'permission as text',
        );

        if($data['user_id']){
            $collection = $collection->where(DB::raw(
            "CASE 
            WHEN
                (SELECT COALESCE(COUNT(user_id), 0) 
                FROM permissions AS permission 
                WHERE permission.permission_id = lib_permissions.id) > 1
            THEN 'YES' 
            WHEN 
                (SELECT COALESCE(COUNT(user_id), 0) 
                FROM permissions AS permission 
                WHERE permission.permission_id = lib_permissions.id AND permission.user_id = '".$data['user_id']."') > 0 
            THEN 'YES' 
            ELSE 'NO' END"), "NO"); 
        }

        if($data['search']){
            $collection = $collection->where('permission', 'like', '%'.$data['search'].'%');
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
        
        $resp = Permission::select('*');

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

                $resp = new Permission;
                $resp->code             = "PER-".(string) Str::uuid();
                $resp->feature          = $fields['feature'];
                $resp->permission       = $fields['permission'];
                $resp->created_by       = Auth::user()->email;
                $resp->changed_by       = Auth::user()->email;
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

            $resp = Permission::where('id', $fields['id'])->first();
            $resp->feature          = $fields['feature'];
            $resp->permission       = $fields['permission'];
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

			Permission::where('id', $fields['id'])->firstOrFail()->delete();

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
