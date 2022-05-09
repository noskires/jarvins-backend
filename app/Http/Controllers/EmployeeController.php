<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use App\Models\Employee;
use DB;
use DataTables;

class EmployeeController extends Controller
{
    public function getAll(Request $request){
    	try {
            $resp = auth()->userOrFail();
            $resp = Employee::select('*');
            return DataTables::of($resp)->make(true);
        } catch(JWTException $e) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function findOne(Request $request){
    	$data = array(
            'id'=>$request->input('id')
        );
        
        $resp = Employee::select('*');

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

                $resp = new Employee;
                $resp->employee_id          = $fields['employee_id'];
                $resp->first_name           = $fields['first_name'];
                $resp->middle_name          = $fields['middle_name'];
                $resp->last_name            = $fields['last_name'];
                $resp->suffix               = $fields['suffix'];
                $resp->position             = $fields['position'];
                $resp->status               = $fields['status'];
                $resp->mobile_number        = $fields['mobile_number'];
                $resp->alternate_contact_number = $fields['alternate_contact_number'];
                $resp->email_address        = $fields['email_address'];
                $resp->entity_code          = $fields['entity_code'];
                $resp->org_unit_code        = $fields['org_unit_code'];
                $resp->leadership_role      = $fields['leadership_role'];
                $resp->immediate_supervisor = $fields['immediate_supervisor'];
                $resp->immediate_head       = $fields['immediate_head'];
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

            $resp = Employee::where('id', $fields['id'])->first();
            $resp->employee_id          = $fields['employee_id'];
            $resp->first_name           = $fields['first_name'];
            $resp->middle_name          = $fields['middle_name'];
            $resp->last_name            = $fields['last_name'];
            $resp->suffix               = $fields['suffix'];
            $resp->position             = $fields['position'];
            $resp->status               = $fields['status'];
            $resp->mobile_number        = $fields['mobile_number'];
            $resp->alternate_contact_number = $fields['alternate_contact_number'];
            $resp->email_address        = $fields['email_address'];
            $resp->entity_code          = $fields['entity_code'];
            $resp->org_unit_code        = $fields['org_unit_code'];
            $resp->leadership_role      = $fields['leadership_role'];
            $resp->immediate_supervisor = $fields['immediate_supervisor'];
            $resp->immediate_head       = $fields['immediate_head'];
            $resp->save();

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

			Employee::where('id', $data['id'])->firstOrFail()->delete();

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
