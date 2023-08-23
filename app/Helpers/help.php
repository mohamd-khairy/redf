<?php

use App\Models\User;
use App\Models\Setting;
use App\Models\Calendar;
use Illuminate\Support\Str;
use App\Models\FormRequestAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

if (!function_exists('setting')) {
    function setting($key = null, $value = null, $group = null)
    {
        if ($key && $value) {
            $item = Setting::create([
                'key' => $key,
                'value' => $value,
                'group' => $group
            ]);

            return $item ? $item[$key] : null;
        } elseif ($key) {
            $item = Setting::where([
                'key' => $key
            ])->first();

            return $item ? $item[$key] : null;
        } else {

            $item = Setting::all()->groupBy('group');
            return $item ?? [];
        }

        return null;
    }
}

if (!function_exists('v_image')) {
    function v_image($ext = null)
    {
        return ($ext === null) ? 'mimes:jpg,png,jpeg,png,gif,bmp' : 'mimes:' . $ext;
    }
}

if (!function_exists('userRoot')) {
    function userRoot()
    {
        return User::find(1);
    }
}

if (!function_exists('isRoot')) {
    function isRoot()
    {
        return auth()->id() == 1 && is_null(auth()->user()->parent_id);
    }
}

if (!function_exists('getPermissions')) {

    function getPermissions($user)
    {
        return $user->roles->map(function ($role) {
            return $role->permissions;
        })->collapse();
    }
}

if (!function_exists('uploadImage')) {
    function uploadImage($storage, $image, $modal)
    {
        $image->store('/' . $modal, $storage);
        return $image->hashName();
    }
}

if (!function_exists('responseSuccess')) {
    function responseSuccess($data = [], $msg = 'success', $code = 200)
    {
        return response()->json([
            'status' => true,
            'message' => $msg,
            'data' => $data
        ], $code);
    }
}

if (!function_exists('unKnownError')) {
    function unKnownError($message = null): JsonResponse|RedirectResponse
    {
        $message = trans('dashboard.something_error') . '' . (env('APP_DEBUG') ? " : $message" : '');

        return request()->expectsJson()
            ? response()->json(['message' => $message], 400)
            : redirect()->back()->with(['status' => 'error', 'message' => $message]);
    }
}

if (!function_exists('is_base64')) {
    function is_base64($s): bool
    {
        return (bool)preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $s);
    }
}

if (!function_exists('responseFail')) {
    function responseFail($msg = 'Fail', $code = 400)
    {
        return response()->json([
            'status' => false,
            'message' => $msg,
        ], $code);
    }
}

if (!function_exists('updateDotEnv')) {
    function updateDotEnv($key, $newValue, $delim = '')
    {
        $path = base_path('.env');
        // get old value from current env
        $oldValue = env($key);

        if (is_bool($oldValue)) {
            $oldValue = true ? 'true' : 'false';
        }

        if (is_bool($newValue)) {
            $newValue = true ? 'true' : 'false';
        }

        // was there any change?
        if ($oldValue === $newValue) {
            return;
        }

        // rewrite file content with changed data
        if (file_exists($path)) {
            // replace current value with new value
            try {
                file_put_contents(
                    $path,
                    str_replace(
                        $key . '=' . $delim . $oldValue . $delim,
                        $key . '=' . $delim . $newValue . $delim,
                        file_get_contents($path)
                    )
                );
            } catch (\Exception $ex) {
                //continue
            }
        }
    }
}

if (!function_exists('resolvePhoto')) {
    function resolvePhoto($image = null, $type = 'none')
    {
        $result =  ($type === 'user'
            ? asset('media/avatar.png')
            : asset('media/blank.png'));

        if (is_null($image)) {
            return $result;
        }

        if (Str::startsWith($image, 'http')) {
            return $image;
        }

        return file_exists('storage/' . $image)  // Storage::exists($image)
            ? url('storage/' . $image)
            : $result;
    }
}

if (!function_exists('saveCalendarFromRequest')) {
    function saveCalendarFromRequest(array $data)
    {

        $validatedData = validator($data, [
            'calendarable_type' => 'nullable|string',
            'calendarable_id' => 'nullable|integer',
            'date' => 'required|date',
            'details' => 'required|string',
            'user_id' => 'nullable|exists:users,id',
            'form_request_id' => 'required|integer',
        ])->validate();
        return Calendar::create($validatedData);
    }
}

if (!function_exists('saveFormRequestAction')) {
    function saveFormRequestAction(array $data)
    {
        $validatedData = validator($data, [
            'formable_type' => 'nullable|string',
            'form_request_id' => 'required|integer',
            'formable_id' => 'nullable|integer',
            'msg' => 'required|string',

        ])->validate();
        return FormRequestAction::create($validatedData);
    }
}
