<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;

use Carbon\Carbon;

use App\Models\Geo;

use DB;
use DataTables;
use Auth;


class GeoController extends Controller
{

    public function getAllRegions(Request $request){ 

        $data = array(
            'id'=>$request->input('id'),
            'search'=>$request->input('search'),//select2 default
        );

        $training_code = null;
        
        $collection = Geo::select(
            // 'code as id',
            'code AS id',
            'name as text',
        )
        ->where('level', 'Reg')
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

    public function getAllProvinces(Request $request){ 

        $data = array(
            'id'=>$request->input('id'),
            'region_code'=>$request->input('region_code'),
            'search'=>$request->input('search'),//select2 default
        );

        $training_code = null;
        
        $collection = Geo::select(
            'code AS id',
            'name as text',
        )
        // ->where('level', 'Prov')
        ->where('next_level_code','LIKE','%'.$data['region_code'].'%')
        // ->orWhere('level', 'Dist')
        ;

        if($data['region_code']!="130000000"){
            $collection = $collection->where('level', 'Prov');
        }else{
            $collection = $collection->where('level', 'Dist ');
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

    public function getAllTowns(Request $request){ 

        $data = array(
            'id'=>$request->input('id'),
            'province_code'=>$request->input('province_code'),
            'search'=>$request->input('search'),//select2 default
        );

        $collection = Geo::select(
            // DB::raw("CAST(code AS VARCHAR(20)) AS id"),
            'code AS id',
            'name as text',
        )
        ->whereIn('level', ['Mun', 'City'])
        // ->where('next_level_code','LIKE','%'.$data['province_code'].'%')
        ;

        if($data['province_code']){
            $collection = $collection->where('next_level_code','LIKE','%'.$data['province_code'].'%');
        }else{
            $collection = $collection->where('next_level_code', '999999999');
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

    public function getAllBrgys(Request $request){ 

        $data = array(
            'id'=>$request->input('id'),
            'town_code'=>$request->input('town_code'),
            'search'=>$request->input('search'),//select2 default
        );

        $training_code = null;
        
        $collection = Geo::select(
            // DB::raw("CAST(code AS VARCHAR(20)) AS id"),
            'code AS id',
            'name as text',
        )
        ->where('level', 'Bgy')
        // ->where('next_level_code','LIKE', $data['town_code'].'%')
        ;

        if($data['town_code']){
            $collection = $collection->where('next_level_code','LIKE','%'.$data['town_code'].'%');
        }else{
            $collection = $collection->where('next_level_code', '999999999');
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
        
        $collection = Geo::select(
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
