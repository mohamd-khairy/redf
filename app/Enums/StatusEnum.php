<?php

namespace App\Enums;

use ArchTech\Enums\InvokableCases;

enum StatusEnum: string
{
    use InvokableCases;

    case active = 'active';
    case notactive = 'notactive';
    case WAIT = 'بانتظار الاجراء';

    case PENDING = 'بانتظار احالتها للموظف المختص';
    case WAIT_SECOND_RULE = 'بانتظار حكم الإستئناف';
    case WAIT_THIRD_RULE = 'بانتظار الحكم النهائي';
    case ASSIGNED = 'بانتظار الحكم الإبتدائي';
}
