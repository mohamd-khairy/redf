<?php

namespace App\Enums;


enum TreatmentTypeEnum: string
{


    case PREPARING_SPEECH = 'اعداد خطاب';
    case CONSULTATION = 'استشاره';
    case NORMAL = 'عاديه';
    case ANOTHER_TREATMENT = 'معاملات اخري';


}
