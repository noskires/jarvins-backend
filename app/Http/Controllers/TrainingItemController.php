<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;

use Carbon\Carbon;

use App\Models\TrainingItem;
use App\Models\Employee;

use DB;
use DataTables;
use Auth;


class TrainingItemController extends Controller
{
    
    public function getAll(Request $request){

        $data = array(
            'employee_id'=>$request->input('employee_id'),
            'training_code'=>$request->input('training_code')
        );
        
    	try {
            $resp = auth()->userOrFail();

            $resp = TrainingItem::select(
                'e.employee_id as employee_id',
                DB::raw("CONCAT(rtrim(CONCAT(e.last_name,' ',COALESCE(e.affix,''))),', ', COALESCE(e.first_name,''),' ', COALESCE(e.middle_name,'')) as employee_name"),
                't.title',
                'training_items.id',
                'training_items.code',
                'training_items.training_code',
                'training_items.certification',
                'training_items.start_date',
                'training_items.end_date',
                'training_items.title_validity_date'
            )
            ->leftjoin('trainings as t','t.code','=','training_items.training_code')
            ->leftjoin('employees as e','e.employee_id','=','training_items.employee_id');
            
            if($data['training_code']){
                $resp = $resp->where('training_items.training_code', $data['training_code']);
            }

            if($data['employee_id']){
                $resp = $resp->where('training_items.employee_id', $data['employee_id']);
            }

            $dtables = DataTables::eloquent($resp)
            ->editColumn('training_items.start_date', function ($resp) {
                if(!$resp->start_date){
                    return null;
                }
                return $date = Carbon::parse($resp->start_date)->format('Y-m-d\TH:i');
            })
            ->editColumn('training_items.end_date', function ($resp) {
                if(!$resp->end_date){
                    return null;
                }
                return $date = Carbon::parse($resp->end_date)->format('Y-m-d\TH:i');
            })
            ->editColumn('training_items.title_validity_date', function ($resp) {
                if(!$resp->title_validity_date){
                    return null;
                }
                return $date = Carbon::parse($resp->title_validity_date)->format('Y/m/d');
            })
            ->filterColumn('employee_name', function($query, $keyword) {
                $sql = "CONCAT(rtrim(CONCAT(e.last_name,' ',COALESCE(e.affix,''))),', ', COALESCE(e.first_name,''),' ', COALESCE(e.middle_name,''))  like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            });

            // return DataTables::of($resp)->make(true);

            return $dtables->toJson();

        } catch(JWTException $e) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function findOne(Request $request){
    	$data = array(
            'id'=>$request->input('id')
        );
        
        $resp = TrainingItem::select('*');

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

        // return $fields['employee_id'][0];

    //  

        // return (string) Str::orderedUuid();

        // $transaction = DB::transaction(function($field) use($fields){
        //     try{

                for($i = 0; $i < count($fields['employee_id']); $i++) {
        
                    $resp = new TrainingItem;
                    $resp->code                 = "TRG-ITM-".(string) Str::uuid();
                    $resp->employee_id          = $fields['employee_id'][$i];
                    $resp->training_code        = $fields['training_code'];
                    $resp->certification        = $fields['certification'];
                    $resp->start_date           = $fields['start_date'];
                    $resp->end_date             = $fields['end_date'];
                    $resp->title_validity_date  = $fields['title_validity_date'];
                    $resp->created_by           = Auth::user()->email;
                    $resp->changed_by           = Auth::user()->email;
                    $resp->save();

                    // $resp                       = TrainingItem::where('id', $resp->id)->first();
                    // $resp->code                 = "TRG-ITM-".(string) Str::uuid($resp->id);
                    // $resp->save();
                }

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

            $resp = TrainingItem::where('id', $fields['id'])->first();
            $resp->employee_id          = $fields['employee_id'];
            $resp->training_code        = $fields['training_code'];
            $resp->certification        = $fields['certification'];
            $resp->start_date           = $fields['start_date'];
            $resp->end_date             = $fields['end_date'];
            $resp->title_validity_date  = $fields['title_validity_date'];
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

			TrainingItem::where('id', $fields['id'])->firstOrFail()->delete();

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
