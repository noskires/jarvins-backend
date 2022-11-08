<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;

use Carbon\Carbon;

use App\Models\UserPermission;

use DB;
use DataTables;
use Auth;


class UserItemController extends Controller
{
    
    public function getAll(Request $request){

        $data = array(
            'code'=>$request->input('code'),
            'user_id'=>$request->input('user_id'),
        );

    	try {
            $resp = auth()->userOrFail();

            // $resp = UserPermission::select('*');

            $resp = UserPermission::select(
                'permissions.id',
                // 'permissions.code',
                'permissions.user_id',
                'permissions.permission_id',
                'lib_permission.feature',
                'lib_permission.permission',
            )
            ->leftjoin('lib_permissions as lib_permission','lib_permission.id','=','permissions.permission_id')
            ;
            
            if($data['user_id']){
                $resp = $resp->where('permissions.user_id', $data['user_id']);
            }


            $dtables = DataTables::eloquent($resp)

            ->filterColumn('permission', function($query, $keyword) {
                $sql = "lib_permission.permission like ?";
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
        
        $collection = UserPermission::select(
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
        
        $resp = UserPermission::select('*');

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

                $resp = new UserPermission;
                $resp->user_id          = $fields['user_id'];
                $resp->permission_id    = $fields['permission_id'];
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

            $resp = UserPermission::where('id', $fields['id'])->first();
            $resp->user_id          = $fields['user_id'];
            $resp->permission_id    = $fields['permission_id'];
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

			UserPermission::where('id', $fields['id'])->firstOrFail()->delete();

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
