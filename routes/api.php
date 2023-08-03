<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AI\AIController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\DroneController;
use App\Http\Controllers\API\EventController;
use App\Http\Controllers\API\FormsController;
use App\Http\Controllers\API\FlightController;
use App\Http\Controllers\API\SettingController;
use App\Http\Controllers\API\LocationController;
use App\Http\Controllers\Api\TemplateController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\API\PermissionController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\Api\OrganizationController;
use App\Http\Controllers\API\VerificationController;
use App\Http\Controllers\API\DetectionTypeController;

Route::group(['prefix' => 'v1'], function () {


    /*********************AuthController***************** */
    Route::post('login', [AuthController::class, 'login']);
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('user', [AuthController::class, 'user']);
        Route::get('logout', [AuthController::class, 'logout']);
        Route::get('users/actions', [UserController::class, 'actions']);
        Route::apiResource('users', UserController::class);
    });

    /*********************VerificationController***************** */
    Route::get('/verify/{id}/{hash}', [VerificationController::class, 'verify']);
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('/verify', [VerificationController::class, 'send']);
    });

    /*********************RoleController***************** */
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::apiResource('roles', RoleController::class);
    });

    /*********************PermissionController***************** */
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('permissions', [PermissionController::class, 'permissions']);
        Route::apiResource('permission', PermissionController::class);
    });

    /*********************SettingController***************** */
    Route::get('general-setting', [SettingController::class, 'generalSettings']);
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('getSetting', [SettingController::class, 'get']);
        Route::get('settings', [SettingController::class, 'index']);
        Route::post('settings', [SettingController::class, 'update']);
        Route::post('set-setting', [SettingController::class, 'store']);
        // Route::apiResource('settings', SettingController::class)->except('update');
    });

    /*********************NotificationController***************** */
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('notifications', [NotificationController::class, 'index']);
        Route::post('notifications', [NotificationController::class, 'update']);
    });

    /*********************Forms***************** */
    Route::group(['middleware' => ['auth:sanctum'], 'prefix' => 'forms'], function (){

         // index
         Route::get('/', [FormsController::class, 'index']);
        // all
        Route::get('all', [FormsController::class, 'all']);
        // change notifications
        Route::put('{userForm}/changeNotifications', [FormsController::class, 'changeNotifications']);
        // Get form with filled data for reviewing
        Route::get('{userForm}/get', [FormsController::class, 'get']);
        // review store
        Route::put('{userForm}/review', [FormsController::class, 'review']);
        // delete
        Route::delete('{userForm}', [FormsController::class, 'destroy']);
        // export
        Route::get('export/{id}', [FormsController::class, 'export']);
        // export form pdf
        Route::get('exportPDF/{userForm}', [FormsController::class, 'exportPDF']);
        // export form word
        Route::get('exportWord/{userForm}', [FormsController::class, 'exportWord']);
    });

    /*********************DepartmentController***************** */
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::apiResource('departments', DepartmentController::class);
    });

    /*********************OrganizationController***************** */
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::apiResource('organizations', OrganizationController::class);
    });

    /*********************OrganizationController***************** */
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::apiResource('templates', TemplateController::class);
    });

});
