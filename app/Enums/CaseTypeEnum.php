<?php

namespace App\Enums;

use ArchTech\Enums\InvokableCases;

enum CaseTypeEnum: string
{
    use InvokableCases;

    case FIRST_RULE = 'صدر الحكم الإبتدائي';
    case SECOND_RULE = 'صدر حكم الإستئناف';
    case THIRD_RULE = 'صدر الحكم النهائي';


    // case WAIT_FIRST_RULE = 'بانتظار الحكم الإبتدائي';
    // case WAIT_SECOND_RULE = 'بانتظار حكم الإستئناف';
    // case WAIT_THIRD_RULE = 'بانتظار الحكم النهائي';
    // case ASSIGNED = 'لدي الموظف المختص';

    // public static function getTranslatedKey(string $caseType): string
    // {
    //     switch ($caseType) {
    //         case self::PENDING: return __('enums.PENDING');
    //         case self::ASSIGNED: return __('enums.ASSIGNED');
    //         case self::PROCESSING: return __('enums.PROCESSING');
    //         case self::CLOSED: return __('enums.CLOSED');
    //         default: return '';
    //     }
    // }

}
