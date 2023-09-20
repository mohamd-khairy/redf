<?php

namespace App\Enums;


enum TreatmentInformationTypeEnum: string
{


    case TOPIC = 'موضوع';
    case OPINION = 'استشاره';
    case STUDY = 'عاديه';
    case NOTICE = 'ملاحظات';
    case PROCEEDINGS = 'وقائع';


}
