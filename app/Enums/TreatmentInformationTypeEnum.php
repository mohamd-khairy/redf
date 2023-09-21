<?php

namespace App\Enums;

enum TreatmentInformationTypeEnum: string
{
    case TOPIC = 'topic';
    case OPINION = 'opinion';
    case STUDY = 'study';
    case NOTICE = 'notice';
    case PROCEEDINGS = 'proceedings';
}
