<?php

namespace App\Http\Controllers\API;

use App\Models\Role;
use App\Models\User;
use App\Enums\UserTypeEnum;
use Illuminate\Support\Str;
use App\Filters\SortFilters;
use Illuminate\Http\Request;
use App\Filters\SearchFilters;
use App\Models\UserInformation;
use App\Services\UploadService;
use Illuminate\Pipeline\Pipeline;
use App\Http\Requests\PageRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\CreateUserInformationRequest;

class UserController extends Controller
{
    public $model = User::class;

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
        $query = User::with(['roles', 'permissions', 'department'])->whereHas('roles', function ($q) {
            $q->where('name', '!=', 'root')->where('name', '!=', 'admin');
        })->where('id', '!=', auth()->id());

        $data = app(Pipeline::class)->send($query)->through([
            SearchFilters::class,
            SortFilters::class,
        ])->thenReturn();

        $data = request('pageSize') == -1 ?  $data->get() : $data->paginate(request('pageSize', 15));

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
            'department_id' => 'required|exists:departments,id',
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
            'department_id' => 'sometimes|exists:departments,id',
            'email' => 'nullable|email|unique:users,email,' . $id,
            'avatar' => 'nullable|image|' . v_image(),
            'roles' => 'nullable|exists:roles,id', // Add the 'roles' validation rule
            'type' => 'sometimes|required|in:user,employee,governorate,company,default:employee',
        ]);

        if ($validate->fails()) {
            return responseFail($validate->messages()->first());
        }

        $data = $request->except('password', 'confirm_password', 'role_id');

        $user = User::findOrFail($id);

        if ($request->file('avatar')) {
            if ($user->avatar) {
                UploadService::delete($user->avatar);
            }
            $data['avatar'] = UploadService::store($request->avatar, 'profile');
        }

        if ($request->password) {
            $data['password'] = bcrypt($request->password);
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

    public function get_users(PageRequest $request)
    {
        $query = User::with('userInformation')->whereNot('type', 'employee')->whereHas('roles', function ($q) {
            $q->where('name', '!=', 'root')->where('name', '!=', 'admin');
        });

        $data = app(Pipeline::class)->send($query)->through([
            SearchFilters::class,
            SortFilters::class,
        ])->thenReturn();

        $data = request('pageSize') == -1 ?  $data->get() : $data->paginate(request('pageSize', 15));

        return responseSuccess(['users' => $data]);
    }

    public function user_type(Request $request)
    {
        $query = User::with('userInformation')->whereHas('roles', function ($q) {
            $q->where('name', '!=', 'root')->where('name', '!=', 'admin');
        });

        // Check if the "type" parameter is present in the request
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        $data = app(Pipeline::class)->send($query)->through([
            SearchFilters::class,
            SortFilters::class,
        ])->thenReturn()->get();

        return responseSuccess(['users' => $data]);
    }

    public function user_employee(Request $request)
    {

        $query = User::where('type', 'employee')->whereHas('roles', function ($q) {
            $q->where('name', '!=', 'root')->where('name', '!=', 'admin');
        });

        $data = app(Pipeline::class)->send($query)->through([
            SearchFilters::class,
            SortFilters::class,
        ])->thenReturn()->get();

        return responseSuccess(['users' => $data]);
    }

    public function store_userInfo(CreateUserInformationRequest $request)
    {
        try {
            // Get the validated data from the request, including the possibly generated email
            $validatedData = $request->validatedWithDefaults();
            // Create a new user
            $newUser = User::create($validatedData + ['password' => Hash::make(Str::random(12)), 'type' => 'user']);
            // Create and save the UserInformation instance

            $newUser->assignRole('employee');
            $userInformation = UserInformation::create([
                'user_id' => $newUser->id, // Set the user_id with the newly created user's ID
                'civil_number' => $validatedData['civil_number'],
            ]);
            return responseSuccess($userInformation, 'User Info has been successfully created');
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
}
