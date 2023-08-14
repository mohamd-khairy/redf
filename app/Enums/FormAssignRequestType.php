<?php
namespace App\Enums;

enum FormAssignRequestType: string
{
    public const CLAIMANT = 'claimant';
    public const EMPLOYEE = 'employee';
    public const DEFENDANT = 'defendant';
}
