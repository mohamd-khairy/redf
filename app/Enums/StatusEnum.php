<?php

namespace App\Enums;

enum StatusEnum: string
{
    case active = 'active';
    case notactive = 'notactive';

    case PENDING = 'بانتظار الحكم الإبتدائي';
    case WAIT_SECOND_RULE = 'بانتظار حكم الإستئناف';
    case WAIT_THIRD_RULE = 'بانتظار الحكم النهائي';
    case ASSIGNED = 'لدي الموظف المختص';
}
