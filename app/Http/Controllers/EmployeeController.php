<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;

use Carbon\Carbon;
use App\Models\Employee;
use App\Models\User;

use DB;
use DataTables;
use Auth;
use JWTAuth;

use PSGC\Facades\Region;

use App\Exports\EmployeesExport;
use Maatwebsite\Excel\Facades\Excel;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class EmployeeController extends Controller
{
    public function __construct(){
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function getAll(Request $request){
    	try {
            
            $resp = auth()->userOrFail();
            $resp = Employee::select(
                'employees.id',
                'employees.employee_id',
                DB::raw("CONCAT(rtrim(CONCAT(employees.last_name,' ',COALESCE(employees.affix,''))),', ', COALESCE(employees.first_name,''),' ', COALESCE(employees.middle_name,'')) as employee_name"),
                'employees.last_name',
                'employees.affix', 
                'employees.first_name', 
                'employees.middle_name',
                'employees.gender',
                'employees.civil_status',
                'employees.date_of_birth',
                'employees.date_hired',
                'employees.position',
                'employees.employment_status',
                'employees.employee_subgroup',
                'employees.mobile_number',
                'employees.alternate_contact_number',
                'employees.email_address',
                'employees.entity_code',
                'employees.org_unit_code',
                'employees.leadership_role',
                'employees.office_floor',
                'employees.office_building',
                'building.name AS office_building_name',
                'employees.immediate_supervisor',
                DB::raw("CONCAT(rtrim(CONCAT(immediate_supervisor.last_name,' ',COALESCE(immediate_supervisor.affix,''))),', ', COALESCE(immediate_supervisor.first_name,''),' ', COALESCE(immediate_supervisor.middle_name,'')) as immediate_supervisor_name"),
                'employees.immediate_head',
                'employees.region as region_code',
                'region.name as region_name',
                'employees.province as province_code',
                'province.name as province_name',
                'employees.city_municipality as city_municipality_code',
                'city_municipality.name as city_municipality_name',
                'employees.brgy as brgy_code',
                'brgy.name as brgy_name',
                'employees.street',
                'employees.lot_no',
                'section.code AS section_code',
                'section.name AS section_name',
                'section.next_level_code AS section_next_level_code',
                'division.code AS division_code',
                'division.name AS division_name',
                'center.code AS center_code',
                'center.name AS center_name',
                'employees.action_type',
                'employees.action_date',
                'employees.remarks',
                
            )
            ->leftjoin('employees AS immediate_supervisor','immediate_supervisor.employee_id','=','employees.immediate_supervisor')

            ->leftjoin('geo AS region','region.code','=','employees.region')
            ->leftjoin('geo AS province','province.code','=','employees.province')
            ->leftjoin('geo AS city_municipality','city_municipality.code','=','employees.city_municipality')
            ->leftjoin('geo AS brgy','brgy.code','=','employees.brgy')

            ->leftjoin('lib_organizations AS section','section.code','=','employees.org_unit_code')
            ->leftjoin('lib_organizations AS division','division.code','=','section.next_level_code')
            ->leftjoin('lib_organizations AS center','center.code','=','division.next_level_code')

            ->leftjoin('lib_buildings AS building','building.id','=','employees.office_building')

            // ->where('employees.employee_id', '541488')
            ;

            // $resp = $resp->where('employee_id', 1);

            // $resp = $resp->orderBy('last_name',  'asc')->orderBy('first_name',  'asc')->orderBy('middle_name',  'asc');

            $dtables = DataTables::eloquent($resp)

            ->editColumn('date_hired_format1', function ($resp) {
                if(!$resp->date_hired){
                    return null;
                } 
                return Carbon::parse($resp->date_hired)->format('M d, Y');
            })

            ->editColumn('los', function ($resp) {
                if(!$resp->date_hired){
                    return null;
                }
                return $date = Carbon::createFromDate($resp->date_hired)->diff(Carbon::now())->format('%y year(s), %m month(s) and %d day(s)');
            })

            ->filterColumn('employee_name', function($query, $keyword) {
                $sql = "CONCAT(rtrim(CONCAT(employees.last_name,' ',COALESCE(employees.affix,''))),', ', COALESCE(employees.first_name,''),' ', COALESCE(employees.middle_name,'')) like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ;

            return $dtables->toJson();

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

    public function getAllSelect2(Request $request){ 

        $data = array(
            'employee_id'=>$request->input('employee_id'),
            'search'=>$request->input('search'),//select2 default
            'term'=>$request->input('term'),//select2 default
            'q'=>$request->input('q'),//select2 default
            'training_code'=>$request->input('training_code'),
            'filter'=>$request->input('filter'),
        );

        $training_code = null;
        
        $collection = DB::table('employees as e')
            ->select(
                'e.employee_id as id',
                DB::raw("CONCAT(rtrim(CONCAT(e.employee_id,'-',e.last_name,' ',COALESCE(e.affix,''))),', ', COALESCE(e.first_name,''),' ', COALESCE(e.middle_name,'')) as text"),    
            );
            
        if($data['training_code']){
            $collection = $collection->where(DB::raw(
            "CASE 
            WHEN 
                (SELECT COALESCE(COUNT(training_code), 0) 
                FROM training_items AS ti 
                WHERE ti.employee_id = e.employee_id AND ti.training_code = '".$data['training_code']."') > 0 
            THEN 'YES' 
            ELSE 'NO' END"), "NO");
        }
        
        if($data['employee_id']){
            $collection = $collection->whereNotIn('e.employee_id', 'like', '%'.$data['employee_id'].'%');
        }

        if($data['term']){
            $collection = $collection->where(
                DB::raw("CONCAT(rtrim(CONCAT(e.employee_id,'-',e.last_name,' ',COALESCE(e.affix,''))),', ', COALESCE(e.first_name,''),' ', COALESCE(e.middle_name,''))"),
                'like', '%'.$data['term'].'%');
        }

        if($data['search']){
            $collection = $collection->where(
                DB::raw("CONCAT(rtrim(CONCAT(e.employee_id,'-',e.last_name,' ',COALESCE(e.affix,''))),', ', COALESCE(e.first_name,''),' ', COALESCE(e.middle_name,''))"),
                'like', '%'.$data['search'].'%');
        }

        if($data['q']){
            $collection = $collection->where(
                DB::raw("CONCAT(rtrim(CONCAT(e.employee_id,'-',e.last_name,' ',COALESCE(e.affix,''))),', ', COALESCE(e.first_name,''),' ', COALESCE(e.middle_name,''))"),
                'like', '%'.$data['q'].'%');
        }

        $query = $collection;

        $collection = $collection->get(); 

        return response()->json([
            'status'=>200,
            'data'=>$collection,
            'items'=>$collection,
            'results'=>$collection,
            'message'=>$query
        ]);

    }

    public function store(Request $request){

        $fields = $request->all();

        // $transaction = DB::transaction(function($field) use($fields){
        //     try{

                $resp = new Employee;
                $resp->employee_id          = $fields['employee_id'];
                $resp->last_name            = $fields['last_name'];
                $resp->first_name           = $fields['first_name'];
                $resp->middle_name          = $fields['middle_name'];
                $resp->affix                = $fields['affix'];
                $resp->gender               = $fields['gender'];
                $resp->civil_status         = $fields['civil_status'];
                $resp->date_of_birth        = $fields['date_of_birth'];
                
                $resp->region               = $fields['region'];
                $resp->province             = $fields['province'];
                $resp->city_municipality    = $fields['city_municipality'];
                $resp->brgy                 = $fields['brgy'];
                $resp->street               = $fields['street'];
                $resp->lot_no               = $fields['lot_no'];

                $resp->date_hired           = $fields['date_hired'];
                $resp->position             = $fields['position'];
                $resp->employment_status    = $fields['employment_status'];
                $resp->employee_subgroup    = $fields['employee_subgroup'];
                $resp->mobile_number        = $fields['mobile_number'];
                // $resp->alternate_contact_number = $fields['alternate_contact_number'];
                $resp->email_address        = $fields['email_address'];
                $resp->entity_code          = $fields['entity_code'];
                // $resp->org_unit_code        = $fields['org_unit_code'];
                $resp->leadership_role      = $fields['leadership_role'];
                $resp->immediate_supervisor = $fields['immediate_supervisor'];
                // $resp->immediate_head       = $fields['immediate_head'];
                $resp->office_floor          = $fields['office_floor'];
                $resp->office_building       = $fields['office_building'];
                $resp->org_unit_code         = $fields['section'];
                $resp->action_type           = $fields['action_type'];
                $resp->action_date           = $fields['action_date'];
                $resp->remarks               = $fields['remarks'];

                $resp->created_by            = Auth::user()->email;
                $resp->changed_by            = Auth::user()->email;
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

            $resp = Employee::where('id', $fields['id'])->first();
            $resp->employee_id          = $fields['employee_id'];
            $resp->first_name           = $fields['first_name'];
            $resp->middle_name          = $fields['middle_name'];
            $resp->last_name            = $fields['last_name'];
            $resp->affix                = $fields['affix'];
            $resp->gender               = $fields['gender'];
            $resp->civil_status         = $fields['civil_status'];
            $resp->date_of_birth        = $fields['date_of_birth'];

            $resp->region               = $fields['region'];
            $resp->province             = $fields['province'];
            $resp->city_municipality    = $fields['city_municipality'];
            $resp->brgy                 = $fields['brgy'];
            $resp->street               = $fields['street'];
            $resp->lot_no               = $fields['lot_no'];


            $resp->date_hired           = $fields['date_hired'];
            $resp->position             = $fields['position'];
            $resp->employment_status    = $fields['employment_status'];
            $resp->employee_subgroup    = $fields['employee_subgroup'];
            $resp->mobile_number        = $fields['mobile_number'];
            // $resp->alternate_contact_number = $fields['alternate_contact_number'];
            $resp->email_address        = $fields['email_address'];
            $resp->entity_code          = $fields['entity_code'];
            $resp->org_unit_code        = $fields['section'];
            $resp->leadership_role      = $fields['leadership_role'];
            $resp->immediate_supervisor = $fields['immediate_supervisor'];
            // $resp->immediate_head       = $fields['immediate_head'];
            $resp->office_floor         = $fields['office_floor'];
            $resp->office_building      = $fields['office_building'];
            $resp->action_type          = $fields['action_type'];
            $resp->action_date          = $fields['action_date'];
            $resp->remarks              = $fields['remarks'];
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

    public function region() {
        // return Region::get();
        // return Region::find('070000000');
        // return Region::includes('provinces')->get();
        return Region::includes(['provinces', 'districts'])->get();  
    }


    public function export() 
    {   
        return Excel::download(new EmployeesExport, 'employees.xlsx');
    }

    public function createAndAssignRoles() {

        $var1 = 29;
        $role = Role::create(['name' => 'user'.$var1]);
        $permission = Permission::create(['name' => 'edit'.$var1]);
    

        $user = User::where('id', 1)->first();
        $role = Role::findById($var1-3);
        
        $user->assignRole($role);

        // Event::dispatch(AuditCustom::class, [$user]);

    }

    public function getRoles() {

        // $role = Role::create(['name' => 'user5']);
        // $permission = Permission::create(['name' => 'edit5']);


        $user = User::where('id', 1)->first();
        // $role = Role::findById(1);
        // $user->assignRole($role);
        
        // return $permissions = $user->permissions;
        return $permissions = $user->getDirectPermissions();

    }

    public function assignRoleToPermission() {

        $role = Role::create(['name' => 'user1']);
        $permission = Permission::create(['name' => 'edit1']);
        $permission->assignRole($role);

        $user = User::where('id', 1)->first();
        $role = Role::findById(1);
        
        $user->assignRole($role);

    }

    

      


}
