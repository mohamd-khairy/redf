<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Drone;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    public $model = Notification::class;

    public function __construct()
    {
        $this->middleware(['auth']);
        // $this->middleware('permission:read-notification', ['only' => ['index']]);
        // $this->middleware('permission:update-notification', ['only' => ['update']]);
    }

    public function index()
    {
        $notifications = [
            'count' => Notification::whereNull('open_at')->where(['notifiable_type' => 'App\Models\User', 'notifiable_id' => auth()->user()->id])
                ->count(),
            'notifications' => Notification::where(['notifiable_type' => 'App\Models\User', 'notifiable_id' => auth()->user()->id])
                ->orderBy('created_at', 'desc')->paginate(10)
        ];

        return responseSuccess($notifications);
    }

    public function update(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'action' => 'required|in:open,read',
            'ids' => 'required_if:action,read',
        ]);

        if ($validate->fails()) {
            return responseFail($validate->messages()->first());
        }

        if (request('action') == 'open') {
            $notifications = Notification::whereNull('open_at')->get();

            foreach ($notifications as $noti) {

                $noti->update(['open_at' => now()]);
            }
        } elseif (request('action') == 'read') {
            $notifications = Notification::whereIn('id', request('ids'))->get();

            foreach ($notifications as $noti) {

                $noti->update(['read_at' => now()]);
            }
        }
        return responseSuccess([], 'notifications has been updated successfully');
    }
}
