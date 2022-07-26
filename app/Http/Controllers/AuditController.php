<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;

use Carbon\Carbon;

use App\Models\Audit;

use DB;
use DataTables;
use Auth;


class AuditController extends Controller
{
    
    public function getAll(Request $request){

        $data = array(
            // 'code'=>$request->input('code'),
        );

    	try {
            // $resp = auth()->userOrFail();

            // $resp = Audit::select('*');

            $resp = Audit::
            select(
            'audits.id',
            'audits.user_type',
            'audits.user_id',
            'audits.event',
            'audits.auditable_id',
            'audits.auditable_type',
            'audits.url',
            'audits.user_agent',
            'audits.tags',
            'audits.old_values',
            'audits.new_values',
            'audits.ip_address',
            'audits.created_at',
            'user.email as email'

            )
            ->leftjoin('users as user','user.id','=','audits.user_id');

            $dtables = DataTables::eloquent($resp)

            ->filterColumn('email', function($query, $keyword) {
                $sql = "user.email like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            });

            return $dtables->toJson();

        } catch(JWTException $e) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

}
