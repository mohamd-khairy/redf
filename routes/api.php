<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\LogController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\HomeController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\FormsController;
use App\Http\Controllers\Api\BranchController;
use App\Http\Controllers\API\SettingController;
use App\Http\Controllers\Api\CalenderController;
use App\Http\Controllers\api\DocumentController;
use App\Http\Controllers\Api\TemplateController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\API\PermissionController;
use App\Http\Controllers\API\FormRequestController;
use App\Http\Controllers\Api\FormSessionController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\Api\OrganizationController;
use App\Http\Controllers\API\VerificationController;
use App\Http\Controllers\Api\FormRequestSideController;

Route::group(['prefix' => 'v1'], function () {

    /*********************AuthController***************** */
    Route::post('login', [AuthController::class, 'login']);
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('user', [AuthController::class, 'user']);
        Route::get('logout', [AuthController::class, 'logout']);
        Route::get('users/actions', [UserController::class, 'actions']);
        Route::apiResource('users', UserController::class);
        Route::get('get-users', [UserController::class, 'get_users']);
        Route::get('user-type', [UserController::class, 'user_type']);
        Route::get('user-employee', [UserController::class, 'user_employee']);
        Route::post('store-userInfo', [UserController::class, 'store_userInfo']);
    });

    /*********************HomeController***************** */
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('index', [HomeController::class, 'index']);
    });

    /*********************VerificationController***************** */
    Route::get('/verify/{id}/{hash}', [VerificationController::class, 'verify']);
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('/verify', [VerificationController::class, 'send']);
    });

    /*********************RoleController***************** */
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('roles/actions', [RoleController::class, 'actions']);
        Route::apiResource('roles', RoleController::class);
    });

    /*********************logsController***************** */
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('logs/actions', [LogController::class, 'actions']);
        Route::get('all-logs', [LogController::class, 'all_logs']);
        Route::get('action-preview/{id}', [LogController::class, 'action_preview']);
    });

    /*********************PermissionController***************** */
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('permissions/actions', [PermissionController::class, 'actions']);
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
        Route::get('notifications/actions', [NotificationController::class, 'actions']);
        Route::get('notifications', [NotificationController::class, 'index']);
        Route::post('notifications', [NotificationController::class, 'update']);
    });

    /*********************DepartmentController***************** */
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('departments/actions', [DepartmentController::class, 'actions']);
        Route::get('user-department', [DepartmentController::class, 'user_department']);
        Route::apiResource('departments', DepartmentController::class);
    });

    /*********************BranchController***************** */
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('branches/actions', [BranchController::class, 'actions']);
        Route::apiResource('branches', BranchController::class);
    });

    /*********************OrganizationController***************** */
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('organizations/actions', [OrganizationController::class, 'actions']);
        Route::apiResource('organizations', OrganizationController::class);
    });

    /*********************TemplateController***************** */
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('templates/actions', [TemplateController::class, 'actions']);
        Route::get('get-template-type', [TemplateController::class, 'getTemplatesType']);
        Route::apiResource('templates', TemplateController::class);
    });

    /*********************TasksController***************** */
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('tasks/actions', [TaskController::class, 'actions']);
        Route::apiResource('tasks', TaskController::class);
    });

    /*********************FormSessionsController***************** */
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::apiResource('formSessions', FormSessionController::class);
    });


    /*********************DocumentController***************** */
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('documents/actions', [DocumentController::class, 'actions']);
        Route::apiResource('documents', DocumentController::class);
    });

    /*********************CalenderController***************** */
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::apiResource('calenders', CalenderController::class);
    });

    /*********************CourtController***************** */
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('/lookup', [HomeController::class, 'lookup']);
    });


    /*********************FormsController***************** */
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('forms/actions', [FormsController::class, 'actions']);
        Route::get('all-form', [FormsController::class, 'allForm']);
        Route::post('create-form', [FormsController::class, 'createForm']);
        Route::put('update-form-basic/{id}', [FormsController::class, 'updateFormBasic']);
        Route::put('update-form/{id}', [FormsController::class, 'updateForm']);
        Route::delete('delete-form/{id}', [FormsController::class, 'deleteForm']);
        Route::get('get-forms', [FormsController::class, 'getFormsByTemplate']);
        Route::get('get-form/{formId}', [FormsController::class, 'listForm']);
    });

    /*********************FormRequestController***************** */
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('store-form-fill', [FormRequestController::class, 'storeFormFill']);
        Route::put('update-form-fill/{id}', [FormRequestController::class, 'updateFormFill']);
        Route::get('get-form-Requests', [FormRequestController::class, 'getFormRequest']);
        Route::get('get-form-Requests/{i}', [FormRequestController::class, 'getFormRequestfill']);
        Route::delete('delete-form-Requests/{id}', [FormRequestController::class, 'deleteFormRequest']);

        Route::post('assign-request', [FormRequestController::class, 'assignRequest']);
         Route::get('all-forms', [FormsController::class, 'allForm']);
        Route::post('form-request-side', [FormRequestController::class , 'storeFormRequestSide']);
        Route::post('form-request-information', [FormRequestController::class , 'formRequestInformation']);
      });
});
