<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use DB;
use DataTables;
use Auth;
use JWTAuth;

use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{

    public function __construct(){
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function list(Request $request){
    	try {
            $user = auth()->userOrFail();
            $users = User::select('*');
            return DataTables::of($users)->make(true);
            // return User::all();
        } catch(JWTException $e) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function update(Request $request){

        $fields = $request->all();

        $transaction = DB::transaction(function($field) use($fields){
        try{

            $user = User::where('id', $fields['id'])->first();
            $user->name         = $fields['name'];
            $user->email         = $fields['email'];
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
