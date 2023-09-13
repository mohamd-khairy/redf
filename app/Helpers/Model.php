<?php

use App\Enums\CaseTypeEnum;
use App\Enums\FormRequestStatus;
use App\Enums\StatusEnum;
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
            $status = CaseTypeEnum::$value();
            return $status;
        } catch (\Throwable $th) {
            $status = null;
        }

        try {
            $status = StatusEnum::$value();
            return $status;
        } catch (\Throwable $th) {
            $status = null;
        }

        try {
            $status = FormRequestStatus::$value();
            return $status;
        } catch (\Throwable $th) {
            $status = $value;
        }

        return $status;
    }
}
