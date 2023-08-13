<?php

namespace App\Http\Controllers\API;

use App\Models\Role;
use App\Models\User;
use App\Filters\SearchFilters;
use App\Filters\SortFilters;
use Illuminate\Http\Request;
use App\Services\UploadService;
use App\Http\Controllers\Controller;
use App\Http\Requests\PageRequest;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        // $this->middleware('permission:read-user', ['only' => ['index', 'show']]);
        // $this->middleware('permission:create-user', ['only' => ['store']]);
        // $this->middleware('permission:update-user', ['only' => ['update']]);
        // $this->middleware('permission:delete-user', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(PageRequest $request)
    {
        $query = User::with(['roles', 'permissions'])->whereHas('roles', function ($q) {
            $q->where('name', '!=', 'root'); //->where('name', '!=', 'admin');
        })->where('id', '!=', auth()->id());

        $data = app(Pipeline::class)->send($query)->through([
            SearchFilters::class,
            SortFilters::class,
        ])->thenReturn();

        $data = $data->paginate(request('pageSize', 15));

        return responseSuccess(['users' => $data]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $validate = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'confirm_password' => 'same:password',
            'avatar' => 'image|mimes:png,jpg,jpeg',
            'type' => 'sometimes|required|in:user,employee,governorate,company,default:employee',
        ]);

        if ($validate->fails()) {
            return responseFail($validate->messages()->first());
        }

        $data = $request->except('confirm_password');
        $data['password'] = bcrypt($request->password);

        if ($request->file('avatar')) {
            $data['avatar'] = UploadService::store($request->avatar, 'profile');
        }


        $user = User::create($data);

        $user->assignRole('employee');

        return responseSuccess($user, 'User has been successfully created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::with('roles')->findOrFail($id);

        return responseSuccess($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
         $validate = Validator::make($request->all(), [
            'password' => 'nullable|min:8',
            'confirm_password' => 'same:password',
            'name' => 'nullable|string',
            'email' => 'nullable|email|unique:users,email,' . $id,
            'avatar' => 'nullable|image|' . v_image(),
            'roles' => 'nullable|exists:roles,id', // Add the 'roles' validation rule
            'type' => 'sometimes|required|in:user,employee,governorate,company,default:employee',

        ]);

        if ($validate->fails()) {
            return responseFail($validate->messages()->first());
        }

        $data = $request->except('confirm_password', 'email', 'role_id');

        $user = User::findOrFail($id);
        // dd($user->getRoleNames());
        if ($request->file('avatar')) {
            if ($user->avatar) {
                UploadService::delete($user->avatar);
            }
            $data['avatar'] = UploadService::store($request->avatar, 'profile');
        }


        $user->update($data);


        if ($request->has('roles')) {
            $role = Role::find($request->input('roles'));
             if ($role) {
                $user->roles()->sync([$role->id]);
            } else {
                return responseSuccess("role not found");
            }
        }
        return responseSuccess($user, 'User has been successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ids = request('ids');
        if ($ids) {
            $ids = explode(',', $ids);

            $users = User::whereIn('id', $ids)->get()->map(function ($item) {
                if ($item->avatar) {
                    UploadService::delete($item->avatar);
                }
                $item->delete();
            });
        } else {
            $user = User::findOrFail($id);

            if ($user->avatar) {
                UploadService::delete($user->avatar);
            }
            $user->delete();
        }
        return responseSuccess([], 'User has been successfully deleted');
    }

    public function actions()
    {
        $ids = is_array(request('ids', [])) ? request('ids', []) : explode(',', request('ids', ''));
        $action = request('action');
        $value = request('value');

        if ($action && !is_null($value)) {
            $users = User::whereIn('id', $ids);

            switch ($action) {
                case 'delete':
                    $users->delete();
                    break;
            }

            return responseSuccess([], 'action set successfully');
        }

        return responseFail('this action is not available');
    }
    public function get_users(Request $request){
        $query = User::whereNot('type','employee');
        $data = app(Pipeline::class)->send($query)->through([
            SearchFilters::class,
            SortFilters::class,
        ])->thenReturn();
        $data = $data->paginate(request('pageSize', 15));
        return responseSuccess(['users' => $data]);
    }

    public function user_type(Request $request){
        $query = User::query();
        // Check if the "type" parameter is present in the request
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }
        $data = app(Pipeline::class)->send($query)->through([
            SearchFilters::class,
            SortFilters::class,
        ])->thenReturn();
        $data = $data->paginate(request('pageSize', 15));
        return responseSuccess(['users' => $data]);
    }
}
