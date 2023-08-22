<?php

namespace App\Enums;

enum CaseTypeEnum: string
{
    case PENDING = 'بانتظار الاجراء';
    case ASSIGNED = 'لدي الموظف المختص';
    case PROCESSING = 'تحت المعالجه';
    case CLOSED = 'تم اكمال الطلب';

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