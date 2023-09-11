<?php

namespace App\Enums;

use ArchTech\Enums\InvokableCases;

enum StatusEnum: string
{
    use InvokableCases;

    case active = 'active';
    case notactive = 'notactive';
    case WAIT = 'بإنتظار الاجراء';
    case WAIT_SESSION = 'بإنتظار الجلسة';

    case PENDING = 'بإنتظار احالتها للموظف المختص';
    case WAIT_SECOND_RULE = 'بإنتظار حكم الإستئناف';
    case WAIT_THIRD_RULE = 'بإنتظار الحكم النهائي';
    case ASSIGNED = 'بإنتظار الحكم الإبتدائي';
}
