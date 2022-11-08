<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\TrainingItemController;
use App\Http\Controllers\ToolsTestEquipmentController;
use App\Http\Controllers\ServiceVehicleController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\ExchangeController;
use App\Http\Controllers\SiteCategoryController;
use App\Http\Controllers\ElectricCompanyController;
use App\Http\Controllers\PssOwnerController;
use App\Http\Controllers\GeoController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\NetworkElementController;
use App\Http\Controllers\BatteryController;
use App\Http\Controllers\RectifierController;
use App\Http\Controllers\RectifierItemController;
use App\Http\Controllers\DcPanelController;
use App\Http\Controllers\DcPanelItemController;
use App\Http\Controllers\ManufacturerController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserItemController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {

    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);

    Route::get('/users', [UserController::class, 'list']);
    Route::post('/users2', [UserController::class, 'list2']);
    Route::get('/me', [UserController::class, 'me']);
    Route::post('/user/update', [UserController::class, 'update']);
    Route::post('/user/save', [UserController::class, 'store']);
    Route::post('/user/delete', [UserController::class, 'remove']);

    Route::post('/user/permission', [UserController::class, 'permission']);

    Route::get('/export', [UserController::class, 'export']);


    Route::post('/user-item/list', [UserItemController::class, 'getAll']);
    Route::post('/user-item/update', [UserItemController::class, 'update']);
    Route::post('/user-item/save', [UserItemController::class, 'store']);
    Route::post('/user-item/delete', [UserItemController::class, 'remove']);

});

// Employee
Route::group([
    'middleware' => 'api',
    // 'prefix' => 'employee'
], function ($router) {

    Route::get('/v1/employee', [EmployeeController::class, 'findOne']);
    Route::get('/v1/employee/select2', [EmployeeController::class, 'getAllSelect2']);
    Route::post('/v1/employee/list', [EmployeeController::class, 'getAll']);
    Route::post('/v1/employee/save', [EmployeeController::class, 'store']);
    Route::post('/v1/employee/update', [EmployeeController::class, 'update']);
    Route::post('/v1/employee/delete', [EmployeeController::class, 'remove']);

    Route::get('/v1/employee/export', [EmployeeController::class, 'export']);

    Route::get('/v1/employee/select2-aurora', [EmployeeController::class, 'select2Aurora']);

    Route::post('/v1/employee/roles-permission', [EmployeeController::class, 'createAndAssignRoles']);
    Route::post('/v1/employee/get-roles', [EmployeeController::class, 'getRoles']);

});

// Employee > Trainings
Route::group([
    'middleware' => 'api'
], function ($router) {

    Route::get('/v1/training', [TrainingController::class, 'findOne']);
    Route::post('/v1/training/list', [TrainingController::class, 'getAll']);
    Route::post('/v1/training/save', [TrainingController::class, 'store']);
    Route::post('/v1/training/update', [TrainingController::class, 'update']);
    Route::post('/v1/training/delete', [TrainingController::class, 'remove']);

    Route::get('/v1/training-history', [TrainingItemController::class, 'findOne']);
    Route::post('/v1/training-history/list', [TrainingItemController::class, 'getAll']);
    Route::post('/v1/training-history/save', [TrainingItemController::class, 'store']);
    Route::post('/v1/training-history/update', [TrainingItemController::class, 'update']);
    Route::post('/v1/training-history/delete', [TrainingItemController::class, 'remove']);

    Route::get('/v1/training-history/employee-list', [TrainingItemController::class, 'employeeList']);

});

// Employee > TTE
Route::group([
    'middleware' => 'api'
], function ($router) {

    Route::get('/v1/tte', [ToolsTestEquipmentController::class, 'findOne']);
    Route::post('/v1/tte/list', [ToolsTestEquipmentController::class, 'getAll']);
    Route::post('/v1/tte/save', [ToolsTestEquipmentController::class, 'store']);
    Route::post('/v1/tte/update', [ToolsTestEquipmentController::class, 'update']);
    Route::post('/v1/tte/delete', [ToolsTestEquipmentController::class, 'remove']);

});

// Employee > SV
Route::group([
    'middleware' => 'api'
], function ($router) {

    Route::get('/v1/sv', [ServiceVehicleController::class, 'findOne']);
    Route::post('/v1/sv/list', [ServiceVehicleController::class, 'getAll']);
    Route::post('/v1/sv/save', [ServiceVehicleController::class, 'store']);
    Route::post('/v1/sv/update', [ServiceVehicleController::class, 'update']);
    Route::post('/v1/sv/delete', [ServiceVehicleController::class, 'remove']);

});

// Inventory > Sites
Route::group([
    'middleware' => 'api'
], function ($router) {

    Route::get('/v1/site', [SiteController::class, 'findOne']);
    Route::post('/v1/site/list', [SiteController::class, 'getAll']);
    Route::get('/v1/site/select2', [SiteController::class, 'getAllSelect2']);
    Route::post('/v1/site/save', [SiteController::class, 'store']);
    Route::post('/v1/site/update', [SiteController::class, 'update']);
    Route::post('/v1/site/delete', [SiteController::class, 'remove']);

});

// Network Elements
Route::group([
    'middleware' => 'api'
], function ($router) {

    Route::get('/v1/ne', [NetworkElementController::class, 'findOne']);
    Route::get('/v1/ne/select2', [NetworkElementController::class, 'getAllSelect2']);
    Route::post('/v1/ne/select2', [NetworkElementController::class, 'getAllSelect2']);
    Route::post('/v1/ne/list', [NetworkElementController::class, 'getAll']);
    Route::post('/v2/ne/list', [NetworkElementController::class, 'getAll2']); //v2
    Route::post('/v1/ne/save', [NetworkElementController::class, 'store']);
    Route::post('/v1/ne/update', [NetworkElementController::class, 'update']);
    Route::post('/v1/ne/delete', [NetworkElementController::class, 'remove']);

});

// Support Facilities > Battery
Route::group([
    'middleware' => 'api'
], function ($router) {

    Route::get('/v1/battery', [BatteryController::class, 'findOne']);
    Route::get('/v1/battery/select2', [BatteryController::class, 'getAllSelect2']);
    Route::post('/v1/battery/list', [BatteryController::class, 'getAll']);
    Route::post('/v1/battery/save', [BatteryController::class, 'store']);
    Route::post('/v1/battery/update', [BatteryController::class, 'update']);
    Route::post('/v1/battery/delete', [BatteryController::class, 'remove']);

});

// Support Facilities > Rectifier
Route::group([
    'middleware' => 'api'
], function ($router) {

    Route::get('/v1/rectifier', [RectifierController::class, 'findOne']);
    Route::get('/v1/rectifier/select2', [RectifierController::class, 'getAllSelect2']);
    Route::post('/v1/rectifier/select2', [RectifierController::class, 'getAllSelect2']);
    Route::post('/v1/rectifier/list', [RectifierController::class, 'getAll']);
    Route::post('/v1/rectifier/save', [RectifierController::class, 'store']);
    Route::post('/v1/rectifier/update', [RectifierController::class, 'update']);
    Route::post('/v1/rectifier/delete', [RectifierController::class, 'remove']);
    
    Route::get('/v1/rectifier-item', [RectifierItemController::class, 'findOne']);
    Route::post('/v1/rectifier-item/select2', [RectifierItemController::class, 'getAllSelect2']);
    Route::post('/v1/rectifier-item/list', [RectifierItemController::class, 'getAll']);
    Route::post('/v1/rectifier-item/save', [RectifierItemController::class, 'store']);
    Route::post('/v1/rectifier-item/update', [RectifierItemController::class, 'update']);
    Route::post('/v1/rectifier-item/delete', [RectifierItemController::class, 'remove']);

});

// Support Facilities > DC Panel
Route::group([
    'middleware' => 'api'
], function ($router) {

    Route::get('/v1/dc-panel', [DcPanelController::class, 'findOne']);
    Route::get('/v1/dc-panel/select2', [DcPanelController::class, 'getAllSelect2']);
    Route::post('/v1/dc-panel/select2', [DcPanelController::class, 'getAllSelect2']);
    Route::post('/v1/dc-panel/list', [DcPanelController::class, 'getAll']);
    Route::post('/v1/dc-panel/save', [DcPanelController::class, 'store']);
    Route::post('/v1/dc-panel/update', [DcPanelController::class, 'update']);
    Route::post('/v1/dc-panel/delete', [DcPanelController::class, 'remove']);
    
    Route::get('/v1/dc-panel-item', [DcPanelItemController::class, 'findOne']);
    Route::post('/v1/dc-panel-item/select2', [DcPanelItemController::class, 'getAllSelect2']);
    Route::post('/v1/dc-panel-item/list', [DcPanelItemController::class, 'getAll']);
    Route::post('/v1/dc-panel-item/save', [DcPanelItemController::class, 'store']);
    Route::post('/v1/dc-panel-item/update', [DcPanelItemController::class, 'update']);
    Route::post('/v1/dc-panel-item/delete', [DcPanelItemController::class, 'remove']);

});

// Libraries
Route::group([
    'middleware' => 'api'
], function ($router) {

    Route::get('/v1/building', [BuildingController::class, 'findOne']);
    Route::get('/v1/building/select2', [BuildingController::class, 'getAllSelect2']);
    Route::post('/v1/building/list', [BuildingController::class, 'getAll']);
    Route::post('/v1/building/save', [BuildingController::class, 'store']);
    Route::post('/v1/building/update', [BuildingController::class, 'update']);
    Route::post('/v1/building/delete', [BuildingController::class, 'remove']);

    Route::get('/v1/exchange', [ExchangeController::class, 'findOne']);
    Route::get('/v1/exchange/select2', [ExchangeController::class, 'getAllSelect2']);
    Route::post('/v1/exchange/list', [ExchangeController::class, 'getAll']);
    Route::post('/v1/exchange/save', [ExchangeController::class, 'store']);
    Route::post('/v1/exchange/update', [ExchangeController::class, 'update']);
    Route::post('/v1/exchange/delete', [ExchangeController::class, 'remove']);

    Route::get('/v1/site-category', [SiteCategoryController::class, 'findOne']);
    Route::get('/v1/site-category/select2', [SiteCategoryController::class, 'getAllSelect2']);
    Route::post('/v1/site-category/list', [SiteCategoryController::class, 'getAll']);
    Route::post('/v1/site-category/save', [SiteCategoryController::class, 'store']);
    Route::post('/v1/site-category/update', [SiteCategoryController::class, 'update']);
    Route::post('/v1/site-category/delete', [SiteCategoryController::class, 'remove']);

    Route::get('/v1/electric-company', [ElectricCompanyController::class, 'findOne']);
    Route::get('/v1/electric-company/select2', [ElectricCompanyController::class, 'getAllSelect2']);
    Route::post('/v1/electric-company/list', [ElectricCompanyController::class, 'getAll']);
    Route::post('/v1/electric-company/save', [ElectricCompanyController::class, 'store']);
    Route::post('/v1/electric-company/update', [ElectricCompanyController::class, 'update']);
    Route::post('/v1/electric-company/delete', [ElectricCompanyController::class, 'remove']);

    Route::get('/v1/pss-owner', [PssOwnerController::class, 'findOne']);
    Route::get('/v1/pss-owner/select2', [PssOwnerController::class, 'getAllSelect2']);
    Route::post('/v1/pss-owner/list', [PssOwnerController::class, 'getAll']);
    Route::post('/v1/pss-owner/save', [PssOwnerController::class, 'store']);
    Route::post('/v1/pss-owner/update', [PssOwnerController::class, 'update']);
    Route::post('/v1/pss-owner/delete', [PssOwnerController::class, 'remove']);

    Route::post('/v1/geo/regions/select2', [GeoController::class, 'getAllRegions']);
    Route::post('/v1/geo/provinces/select2', [GeoController::class, 'getAllProvinces']);
    Route::post('/v1/geo/towns/select2', [GeoController::class, 'getAllTowns']);
    Route::post('/v1/geo/barangays/select2', [GeoController::class, 'getAllBrgys']);
    Route::get('/v1/geo/nothing', [GeoController::class, 'getNothing']);

    Route::get('/v1/manufacturer', [ManufacturerController::class, 'findOne']);
    Route::get('/v1/manufacturer/select2', [ManufacturerController::class, 'getAllSelect2']);
    Route::post('/v1/manufacturer/list', [ManufacturerController::class, 'getAll']);
    Route::post('/v1/manufacturer/save', [ManufacturerController::class, 'store']);
    Route::post('/v1/manufacturer/update', [ManufacturerController::class, 'update']);
    Route::post('/v1/manufacturer/delete', [ManufacturerController::class, 'remove']);

    Route::get('/v1/permission', [PermissionController::class, 'findOne']);
    Route::get('/v1/permission/select2', [PermissionController::class, 'getAllSelect2']);
    Route::post('/v1/permission/list', [PermissionController::class, 'getAll']);
    Route::post('/v1/permission/save', [PermissionController::class, 'store']);
    Route::post('/v1/permission/update', [PermissionController::class, 'update']);
    Route::post('/v1/permission/delete', [PermissionController::class, 'remove']);

    Route::post('/v1/organization/center/select2', [OrganizationController::class, 'getAllCenter']);
    Route::post('/v1/organization/division/select2', [OrganizationController::class, 'getAllDivision']);
    Route::post('/v1/organization/section/select2', [OrganizationController::class, 'getAllSection']);
    Route::get('/v1/organization/nothing', [OrganizationController::class, 'getNothing']);
    
});

// Audit Logs
Route::group([
    'middleware' => 'api'
], function ($router) {

    Route::post('/v1/audit/list', [AuditController::class, 'getAll']);

});

Route::get('/regions', [EmployeeController::class, 'region']);


Route::get('/array_diff', function(){
// return "asdf";
// $array1 = array("a" => "sky", "star", "moon", "cloud", "moon");
// $array2 = array("b" => "sky", "sun", "moon");

$array1 = ["1", "2", "3"];
$array2 = ["5", "6"];
 
// Comparing the values
$result = array_diff($array2, $array1);
// print_r($result);
return $result;

// result 1
// $result = array_diff($array1, $array2);
// Array
// (
// [0] => star
// [2] => cloud
// )

// result 2
// $result = array_diff($array2, $array1);
// Array
// (
// [0] => sun
// )

});

