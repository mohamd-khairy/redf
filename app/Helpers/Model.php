<?php


use App\Models\User;
use App\Enums\UserTypeEnum;

if (!function_exists('userType')) {
    function userType($type = UserTypeEnum::EMPLOYEE)
    {
        return User::where('type', $type)->get();
    }
}
