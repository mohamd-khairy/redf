<?php

namespace App\Enums;

enum TreatmentTypeEnum: string
{
    case PREPARING_SPEECH = 'preparing_speech';
    case CONSULTATION = 'consultation';
    case NORMAL = 'normal';
    case ANOTHER_TREATMENT = 'another_treatment';
}
