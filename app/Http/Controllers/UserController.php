<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserPermission;
use DB;
use DataTables;
use Auth;
use JWTAuth;

use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;


class UserController extends Controller
{

    use HasRoles;

    public function __construct(){
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function list(Request $request){
    	try {
            $resp = auth()->userOrFail();
            $resp = User::select(
                '*'
                // 'users.id',
                // 'users.email',
                // 'users.email',
            );
            // return DataTables::of($users)->make(true);
            // return User::all();

            $dtables = DataTables::eloquent($resp)

            ->filterColumn('users.name', function($query, $keyword) {
                $sql = "users.name like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            });

            return $dtables->toJson();

        } catch(JWTException $e) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function list2(Request $request){
    	try {
            $user = auth()->userOrFail();
            $users = User::select('*');
            return DataTables::of($users)->make(true);
            // return User::all();
        } catch(JWTException $e) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function store(Request $request){

        $fields = $request->all();
        
        // $transaction = DB::transaction(function($field) use($fields){
        //     try{

                $resp = new User;
                $resp->name             = $fields['name'];
                $resp->email            = $fields['email'];
                $resp->password         = $fields['password'];
                $resp->section_id       = $fields['section_id'];
                $resp->is_admin         = $fields['is_admin'];
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

        $transaction = DB::transaction(function($field) use($fields){
        try{

            $user = User::where('id', $fields['id'])->first();
            $user->name             = $fields['name'];
            $user->email            = $fields['email'];
            $user->section_id       = $fields['section_id'];
            $user->is_admin         = $fields['is_admin'];
            $user->save();

            return response()->json([
                'status' => 200,
                'data' => null,
                'message' => 'Successfully updated.'
            ]);

          }
          catch (\Exception $e) 
          {
            return response()->json([
              'status' => 500,
              'data' => null,
              'message' => 'Error, please try again!'
            ]);
          }
        });

        return $transaction;
    }

    public function remove(Request $request){

	    $fields = $request->all();

	    // $transaction = DB::transaction(function($field) use($fields){
	    // try{

			User::where('id', $fields['id'])->firstOrFail()->delete();

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

    public function permission(Request $request){
        // return $id = auth()->user()->id;
        return UserPermission::select(
            'permissions.id',
            'permissions.user_id',
            'permissions.permission_id',
            'lib_permission.permission'
        )
        ->leftjoin('lib_permissions AS lib_permission','lib_permission.id','=','permissions.permission_id')
        ->where('permissions.user_id', auth()->user()->id)
        ->pluck('lib_permission.permission')->toArray();

    }

    function me() {

        return $this->user = JWTAuth::parseToken()->authenticate();

        // try {
        //     if(Auth::check()) {
        //         return response()->json(['message' => 'Authorized'], 200);
        //     }else{
        //         return response()->json(['message' => 'Unauthorized'], 401);
        //     }
            
        // }
        // catch (\Exception $e) {
        //     return response()->json(['message' => 'Error'], 500);
        // }
        
    }

    public function export() 
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }


}
