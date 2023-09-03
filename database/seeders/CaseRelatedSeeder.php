<?php

namespace Database\Seeders;

use App\Enums\FormEnum;
use App\Models\Form;
use App\Models\FormPage;
use App\Models\FormPageItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CaseRelatedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $courts = json_encode([
            [
                'text' => 'الجزائية',
            ],
            [
                'text' => 'الحقوقية',
            ],
            [
                'text' => 'الأحوال الشخصية',
            ],
            [
                'text' => 'التجارية',
            ],
            [
                'text' => 'العمالية',
            ],
            [
                'text' => 'الإدارية',
            ],
        ]);

        $form1 = Form::firstOrCreate([
            'id' => FormEnum::RESUME_CASE_FORM,
            'name' => 'طلب استئناف',
            'description' => 'طلب استئناف',
            'user_id' => 1,
            'main' => 0,
            'template_id' => 1,
        ]);

        $form_page1 = FormPage::create([
            'title' => 'طلب استئناف',
            'form_id' => $form1->id
        ]);


        FormPageItem::create([
            'label' => 'فرع المحكمة الادارية',
            'type' => 'text',
            'enabled' => 1,
            'required' => 1,
            'website_view' => 1,
            'notes' => '',
            'comment' => '',
            'width' => 'col-12',
            'input_type' => 'text',
            'height' => '',
            'length' => '',
            'childList' => json_encode([]),
            'form_page_id' => $form_page1->id,
            'key'=>'branch'
        ]);

        FormPageItem::create([
            'label' => 'الدائرة',
            'type' => 'select',
            'enabled' => 1,
            'required' => 1,
            'website_view' => 1,
            'notes' => '',
            'comment' => '',
            'width' => 'col-12',
            'input_type' => 'text',
            'height' => '',
            'length' => '20',
            'childList' => $courts,
            'form_page_id' => $form_page1->id,
            'key'=>'court'
        ]);

        FormPageItem::create([
            'label' => 'العام الهجرى',
            'type' => 'text',
            'enabled' => 1,
            'required' => 1,
            'website_view' => 1,
            'notes' => '',
            'comment' => '',
            'width' => 'col-12',
            'input_type' => 'text',
            'height' => '',
            'length' => '',
            'childList' => json_encode([]),
            'form_page_id' => $form_page1->id,
            'key'=>'hijri_year'
        ]);

        FormPageItem::create([
            'label' => 'منطقة صندوق التنمية العقارية',
            'type' => 'text',
            'enabled' => 1,
            'required' => 1,
            'website_view' => 1,
            'notes' => '',
            'comment' => '',
            'width' => 'col-12',
            'input_type' => 'text',
            'height' => '',
            'length' => '',
            'childList' => json_encode([]),
            'form_page_id' => $form_page1->id,
            'key'=>'redf_location'
        ]);

        FormPageItem::create([
            'label' => 'القاضى',
            'type' => 'text',
            'enabled' => 1,
            'required' => 1,
            'website_view' => 1,
            'notes' => '',
            'comment' => '',
            'width' => 'col-12',
            'input_type' => 'text',
            'height' => '',
            'length' => '',
            'childList' => json_encode([]),
            'form_page_id' => $form_page1->id,
            'key'=>'judge'
        ]);

        FormPageItem::create([
            'label' => 'أسباب الحكم',
            'type' => 'textarea',
            'enabled' => 1,
            'required' => 1,
            'website_view' => 1,
            'notes' => '',
            'comment' => '',
            'width' => 'col-12',
            'input_type' => 'text',
            'height' => '',
            'length' => '500',
            'childList' => json_encode([]),
            'form_page_id' => $form_page1->id,
            'key'=>'judgment_reasons'
        ]);

        FormPageItem::create([
            'label' => 'الحكم',
            'type' => 'textarea',
            'enabled' => 1,
            'required' => 1,
            'website_view' => 1,
            'notes' => '',
            'comment' => '',
            'width' => 'col-12',
            'input_type' => 'text',
            'height' => '',
            'length' => '500',
            'childList' => json_encode([]),
            'form_page_id' => $form_page1->id,
            'key'=>'judgment'
        ]);

        FormPageItem::create([
            'label' => 'يؤكد صندوق التنمية',
            'type' => 'textarea',
            'enabled' => 1,
            'required' => 1,
            'website_view' => 1,
            'notes' => '',
            'comment' => '',
            'width' => 'col-12',
            'input_type' => 'text',
            'height' => '',
            'length' => '500',
            'childList' => json_encode([]),
            'form_page_id' => $form_page1->id,
            'key'=>'judgment_proves'
        ]);
    }
}
