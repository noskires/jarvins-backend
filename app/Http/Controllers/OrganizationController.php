<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;

use Carbon\Carbon;

use App\Models\Organization;

use DB;
use DataTables;
use Auth;


class OrganizationController extends Controller
{

    public function getAllCenter(Request $request){ 

        $data = array(
            'id'=>$request->input('id'),
            'search'=>$request->input('search'),//select2 default
        );

        $training_code = null;
        
        $collection = Organization::select(
            // 'code as id',
            DB::raw("CAST(code AS VARCHAR(20)) AS id"),
            'name as text',
        )
        ->where('level', 'Center')
        ->orderBy('code', 'ASC');

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

    public function getAllDivision(Request $request){ 

        $data = array(
            'id'=>$request->input('id'),
            'center_code'=>$request->input('center_code'),
            'search'=>$request->input('search'),//select2 default
        );

        $training_code = null;
        
        $collection = Organization::select(
            DB::raw("CAST(code AS VARCHAR(20)) AS id"),
            'name as text',
        )
        ->where('next_level_code','LIKE','%'.$data['center_code'].'%')
        ->where('level', 'Division');

        if($data['search']){
            $collection = $collection->where('name', 'like', $data['search'].'%');
        }

        $query = $collection;

        $collection = $collection->get(); 

        return response()->json([
            'status'=>200,
            'results'=>$collection,
        ]);

    }

    public function getAllSection(Request $request){ 

        $data = array(
            'id'=>$request->input('id'),
            'division_code'=>$request->input('division_code'),
            'search'=>$request->input('search'),//select2 default
        );

        $collection = Organization::select(
            DB::raw("CAST(code AS VARCHAR(20)) AS id"),
            'name as text',
            'next_level_code'
        )
        ->where('level', 'section')
        // ->where('next_level_code','LIKE','%'.$data['division_code'].'%')
        ;

        if($data['division_code']){
            $collection = $collection->where('next_level_code','LIKE','%'.$data['division_code'].'%');
        }

        if($data['search']){
            $collection = $collection->where('name', 'like', $data['search'].'%');
        }

        $query = $collection;

        $collection = $collection->get(); 

        return response()->json([
            'status'=>200,
            'results'=>$collection,
        ]);

    }

    public function getNothing(Request $request){ 

        $data = array(
            'id'=>$request->input('id'),
            'search'=>$request->input('search'),//select2 default
        );

        $training_code = null;
        
        $collection = Organization::select(
            'code as id',
            'name as text',
        )
        ->where('code', '99999999')
        ;

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

}
