<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;


use App\Models\Site;
use DB;
use DataTables;
use Auth;

class SiteController extends Controller
{
    public function getAll(Request $request){
    	try {
            $user = auth()->userOrFail();
            $users = Site::select(
                'sites.id',
                'sites.code',
                'sites.name',
                'sites.status',
                'sites.category_code',
                'sc.name AS category_name',
                'sites.cabinet_type',
                'sites.region',
                'sites.province',
                'sites.city_municipality',
                'sites.brgy',
                'sites.street',
                'sites.lot_no',
                DB::raw("
                    CONCAT(
                        (CASE WHEN (sites.region IS NULL) THEN '' ELSE CONCAT(sites.region,', ') END),
                        (CASE WHEN (sites.province IS NULL) THEN '' ELSE CONCAT(sites.province,', ') END),
                        (CASE WHEN (sites.city_municipality IS NULL) THEN '' ELSE CONCAT(sites.city_municipality,', ') END),
                        (CASE WHEN (sites.brgy IS NULL) THEN '' ELSE CONCAT(sites.brgy,', ') END),
                        (CASE WHEN (sites.street IS NULL) THEN '' ELSE CONCAT(sites.street) END)
                    ) as address2
                "),

                DB::raw("CONCAT(COALESCE(region.name,''),', ', COALESCE(province.name,''),', ', COALESCE(city_municipality.name,''), ', ',COALESCE(brgy.name,''),', ',COALESCE(sites.street,'')) as address"),
                'sites.exchange_code',
                'exchange.name AS exchange_name',
                'sites.building_code',
                'building.name AS building_name',
                'sites.building_floor',
                'sites.equipment_room',
                'sites.room_existing_tag',
                'sites.longitude',
                'sites.latitude',
                'sites.electric_company_code',
                'ec.name AS electric_company_name',
                'ec.sin AS electric_company_sin',
                'ec.meter AS electric_company_meter',
                'sites.pss_owner_code',
                'po.name AS pss_owner_name',
                'sites.region as region_code',
                'region.name as region_name',
                'sites.province as province_code',
                'province.name as province_name',
                'sites.city_municipality as city_municipality_code',
                'city_municipality.name as city_municipality_name',
                'sites.brgy as brgy_code',
                'brgy.name as brgy_name',
            )
        
            ->leftjoin('geo AS region','region.code','=','sites.region')
            ->leftjoin('geo AS province','province.code','=','sites.province')
            ->leftjoin('geo AS city_municipality','city_municipality.code','=','sites.city_municipality')
            ->leftjoin('geo AS brgy','brgy.code','=','sites.brgy')

            ->leftjoin('lib_site_categories AS sc','sc.id','=','sites.category_code')
            ->leftjoin('lib_buildings AS building','building.id','=','sites.building_code')
            ->leftjoin('lib_exchanges AS exchange','exchange.id','=','sites.exchange_code')
            ->leftjoin('lib_electric_companies AS ec','ec.id','=','sites.electric_company_code')
            ->leftjoin('lib_pss_owners AS po','po.id','=','sites.pss_owner_code')
            ;
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
        
        $resp = Site::select('id', 'site_id', 'site_name', 'site_status', 'site_category');

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

                $resp = new Site;
                $resp->code                 = $fields['code'];
                $resp->name                 = $fields['name'];
                $resp->status               = $fields['status'];
                $resp->category_code        = $fields['category_code'];
                $resp->cabinet_type         = $fields['cabinet_type'];
                $resp->region               = $fields['region'];
                $resp->province             = $fields['province'];
                $resp->city_municipality    = $fields['city_municipality'];
                $resp->brgy                 = $fields['brgy'];
                $resp->street               = $fields['street'];
                $resp->lot_no               = $fields['lot_no'];
                $resp->longitude            = $fields['longitude'];
                $resp->latitude             = $fields['latitude'];
                $resp->building_code        = $fields['building_code'];
                $resp->exchange_code        = $fields['exchange_code'];
                $resp->electric_company_code= $fields['electric_company_code'];
                $resp->pss_owner_code       = $fields['pss_owner_code'];
                $resp->created_by           = Auth::user()->email;
                $resp->changed_by           = Auth::user()->email;
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

            $resp = Site::where('id', $fields['id'])->first();
            $resp->code                 = $fields['code'];
            $resp->name                 = $fields['name'];
            $resp->status               = $fields['status'];
            $resp->category_code        = $fields['category_code'];
            $resp->cabinet_type         = $fields['cabinet_type'];
            $resp->region               = $fields['region'];
            $resp->province             = $fields['province'];
            $resp->city_municipality    = $fields['city_municipality'];
            $resp->brgy                 = $fields['brgy'];
            $resp->street               = $fields['street'];
            $resp->lot_no               = $fields['lot_no'];
            $resp->longitude            = $fields['longitude'];
            $resp->latitude             = $fields['latitude'];
            $resp->building_code        = $fields['building_code'];
            $resp->exchange_code        = $fields['exchange_code'];
            $resp->electric_company_code= $fields['electric_company_code'];
            $resp->pss_owner_code       = $fields['pss_owner_code'];
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
