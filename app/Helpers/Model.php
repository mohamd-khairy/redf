<?php

use App\Enums\CaseTypeEnum;
use App\Enums\FormRequestStatus;
use App\Models\User;
use App\Enums\UserTypeEnum;

if (!function_exists('userType')) {
    function userType($type = UserTypeEnum::EMPLOYEE)
    {
        return User::where('type', $type)->get();
    }
}


if (!function_exists('DisplayStatus')) {
    function DisplayStatus($value)
    {
        try {
            $status = $value ? CaseTypeEnum::$value() : null;
        } catch (\Throwable $th) {
            $status = null;
        }

        try {
            $status = $status ?  $status : FormRequestStatus::$value();
        } catch (\Throwable $th) {
            $status = $value;
        }

        return $status;
    }
}
