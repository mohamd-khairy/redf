<?php

namespace App\Enums;

enum FormEnum: int
{
    case CASE = 1;                     // قضيه
    case LEGAL_ADVICE = 2;             // استشاره قانونيه
    case LEGAL_REVIEW = 3;             // مراجعه
    case DEFENCE_FORM = 4;             // دفاع
    case CLAIM_CASE_FORM = 5;          // رفع دعوي
    case RESUME_CASE_FORM = 6;         // استئناف
    case SOLICITATION_CASE_FORM = 7;   // التماس
    case OBJECTION_CASE_FORM = 8;      // اعتراض
    case IMPLEMENTATION_CASE_FORM = 9; // تنفيذ
}
