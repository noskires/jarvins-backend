<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;


use App\Models\Site;
use DB;
use DataTables;

class SiteController extends Controller
{
    public function getAll(Request $request){
    	try {
            $user = auth()->userOrFail();
            $users = Site::select('*');
            return DataTables::of($users)->make(true);
            // return User::all();
        } catch(JWTException $e) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function findOne(Request $request){
    	$data = array(
            'id'=>$request->input('id')
        );
        
        $site = Site::select('id', 'site_id', 'site_name', 'site_status', 'site_category');

        if ($data['id']){
            $site = $site->where('id', $data['id']);
        }

        $site = $site->get();

        return response()->json([
            'status'=>200,
            'data'=>$site,
            'count'=>$site->count(),
            'message'=>''
        ],200,[], JSON_NUMERIC_CHECK);
    }

    public function store(Request $request){

        $fields = $request->all();

        $transaction = DB::transaction(function($field) use($fields){
            try{

                $site = new Site;
                $site->site_id       = $fields['site_id'];
                $site->site_name     = $fields['site_name'];
                $site->site_status   = $fields['site_status'];
                $site->site_category = $fields['site_category'];
                $site->save();

                return response()->json([
                    'status' => 200,
                    'data' => null,
                    'message' => 'Successfully saved.'
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

    public function update(Request $request){

        $fields = $request->all();

        $transaction = DB::transaction(function($field) use($fields){
        try{

            $site = Site::where('id', $fields['id'])->first();
            $site->site_id       = $fields['site_id'];
            $site->site_name     = $fields['site_name'];
            $site->site_status   = $fields['site_status'];
            $site->site_category = $fields['site_category'];
            $site->save();

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

	    $datas = Input::post();

	    $transaction = DB::transaction(function($datas) use($data){
	    try{

			Site::where('id', $data['id'])->firstOrFail()->delete();

	        return response()->json([
	            'status' => 200,
	            'data' => 'null',
	            'message' => 'Successfully deleted.'
	        ]);

	      }
	      catch (\Exception $e) 
	      {
	          return response()->json([
	            'status' => 500,
	            'data' => 'null',
	            'message' => 'Error, please try again!'
	        ]);
	      }
	    });

   	 	return $transaction;
  	}
}
