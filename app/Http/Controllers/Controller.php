<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function actions()
    {
        $ids = is_array(request('ids', [])) ? request('ids', []) : explode(',', request('ids', ''));
        $action = request('action');
        $value = request('value');

        if ($action && !is_null($value)) {
            $users = $this->model::whereIn('id', $ids);

            switch ($action) {
                case 'delete':
                    $users->delete();
                    break;
            }

            return responseSuccess([], 'action set successfully');
        }

        return responseFail('this action is not available');
    }
}
