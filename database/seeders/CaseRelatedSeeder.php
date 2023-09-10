<?php

namespace Database\Seeders;

use App\Enums\FormEnum;
use App\Models\Branch;
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
        $form1 = Form::firstOrCreate([
            'id' => FormEnum::RESUME_CASE_FORM,
            'name' => 'طلب استئناف',
            'description' => 'طلب استئناف',
            'user_id' => 1,
            'main' => 0,
            'template_id' => 1,
        ]);

        $form2 = Form::firstOrCreate([
            'id' => FormEnum::OBJECTION_CASE_FORM,
            'name' => 'طلب اعتراض بالعليا',
            'description' => 'طلب اعتراض بالعليا',
            'user_id' => 1,
            'main' => 0,
            'template_id' => 1,
        ]);

        $form3 = Form::firstOrCreate([
            'id' => FormEnum::SOLICITATION_CASE_FORM,
            'name' => 'طلب التماس',
            'description' => 'طلب التماس',
            'user_id' => 1,
            'main' => 0,
            'template_id' => 1,
        ]);

        $form4 = Form::firstOrCreate([
            'id' => FormEnum::IMPLEMENTATION_CASE_FORM,
            'name' => 'خطاب تنفيذ',
            'description' => 'خطاب تنفيذ',
            'user_id' => 1,
            'main' => 0,
            'template_id' => 1,
        ]);

        $form5 = Form::firstOrCreate([
            'id' => FormEnum::CLAIM_CASE_FORM,
            'name' => 'لائحة دعوى',
            'description' => 'لائحة دعوى',
            'user_id' => 1,
            'main' => 0,
            'template_id' => 1,
        ]);

        $form6 = Form::firstOrCreate([
            'id' => FormEnum::DEFENCE_CASE_FORM,
            'name' => 'مذكرة الدفاع',
            'description' => 'مذكرة الدفاع',
            'user_id' => 1,
            'main' => 0,
            'template_id' => 1,
        ]);

        $form_page1 = FormPage::create([
            'title' => 'طلب استئناف',
            'form_id' => $form1->id
        ]);

        $form_page2 = FormPage::create([
            'title' => 'طلب اعتراض بالعليا',
            'form_id' => $form2->id
        ]);

        $form_page3 = FormPage::create([
            'title' => 'طلب التماس',
            'form_id' => $form3->id
        ]);

        $form_page4 = FormPage::create([
            'title' => 'خطاب تنفيذ',
            'form_id' => $form4->id
        ]);

        $form_page5 = FormPage::create([
            'title' => 'لائحة دعوى',
            'form_id' => $form5->id
        ]);

        $form_page6 = FormPage::create([
            'title' => 'مذكرة الدفاع',
            'form_id' => $form6->id
        ]);


        foreach ([
            $form_page1->id,
            $form_page2->id,
            $form_page3->id,
            $form_page4->id,
            $form_page5->id,
            $form_page6->id,
        ] as  $form_page_id) {


            FormPageItem::create([
                'label' => 'اسم الملف',
                'type' => 'textarea',
                'enabled' => 1,
                'required' => 0,
                'website_view' => 1,
                'notes' => '',
                'comment' => '',
                'width' => 'col-12',
                'input_type' => 'text',
                'height' => '',
                'length' => '500',
                'childList' => json_encode([]),
                'form_page_id' => $form_page_id
            ]);

            FormPageItem::create([
                'label' => 'ملف مرفق',
                'type' => 'file',
                'enabled' => 1,
                'required' => 0,
                'website_view' => 1,
                'notes' => '',
                'comment' => '',
                'width' => 'col-12',
                'input_type' => 'text',
                'height' => '',
                'childList' => json_encode([]),
                'form_page_id' => $form_page_id
            ]);
        }

        // // first model
        // FormPageItem::create([
        //     'label' => 'فرع المحكمة الادارية',
        //     'type' => 'text',
        //     'enabled' => 1,
        //     'required' => 1,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '20',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page1->id,
        //     'key' => 'branch'
        // ]);

        // FormPageItem::create([
        //     'label' => 'الدائرة',
        //     'type' => 'text',
        //     'enabled' => 1,
        //     'required' => 1,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '20',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page1->id,
        //     'key' => 'court'
        // ]);

        // FormPageItem::create([
        //     'label' => 'العام الهجرى',
        //     'type' => 'text',
        //     'enabled' => 1,
        //     'required' => 1,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page1->id,
        //     'key' => 'hijri_year'
        // ]);

        // FormPageItem::create([
        //     'label' => 'منطقة صندوق التنمية العقارية',
        //     'type' => 'text',
        //     'enabled' => 1,
        //     'required' => 1,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page1->id,
        //     'key' => 'redf_location'
        // ]);

        // FormPageItem::create([
        //     'label' => 'القاضى',
        //     'type' => 'text',
        //     'enabled' => 1,
        //     'required' => 1,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page1->id,
        //     'key' => 'judge'
        // ]);

        // FormPageItem::create([
        //     'label' => 'أسباب الحكم',
        //     'type' => 'textarea',
        //     'enabled' => 1,
        //     'required' => 1,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '500',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page1->id,
        //     'key' => 'judgment_reasons'
        // ]);

        // FormPageItem::create([
        //     'label' => 'الحكم',
        //     'type' => 'textarea',
        //     'enabled' => 1,
        //     'required' => 1,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '500',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page1->id,
        //     'key' => 'judgment'
        // ]);

        // FormPageItem::create([
        //     'label' => 'يؤكد صندوق التنمية',
        //     'type' => 'textarea',
        //     'enabled' => 1,
        //     'required' => 1,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '500',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page1->id,
        //     'key'=>'judgment_proves'
        // ]);

        // // second model
        // FormPageItem::create([
        //     'label' => 'العام الهجرى',
        //     'type' => 'text',
        //     'enabled' => 1,
        //     'required' => 1,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page2->id,
        //     'key'=>'hijri_year'
        // ]);

        // FormPageItem::create([
        //     'label' => 'المستأنف',
        //     'type' => 'text',
        //     'enabled' => 1,
        //     'required' => 1,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page2->id,
        //     'key'=>'appellant'
        // ]);

        // FormPageItem::create([
        //     'label' => 'رقم هوية المستأنف',
        //     'type' => 'text',
        //     'enabled' => 1,
        //     'required' => 1,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page2->id,
        //     'key'=>'appellant_id'
        // ]);

        // FormPageItem::create([
        //     'label' => 'ضد المستأنف',
        //     'type' => 'text',
        //     'enabled' => 1,
        //     'required' => 1,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page2->id,
        //     'key'=>'appellant_against'
        // ]);

        // FormPageItem::create([
        //     'label' => 'الحكم الابتدائى',
        //     'type' => 'label',
        //     'enabled' => 1,
        //     'required' => 0,
        //     'website_view' => 1,
        //     'notes' => 'right',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'text',
        //     'height' => '115px',
        //     'length' => '',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page2->id,
        //     'key'=>''
        // ]);

        // FormPageItem::create([
        //     'label' => 'الدائرة',
        //     'type' => 'text',
        //     'enabled' => 1,
        //     'required' => 0,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page2->id,
        //     'key'=>'court_primary_ruling'
        // ]);

        // FormPageItem::create([
        //     'label' => 'المحكمة الادارية',
        //     'type' => 'text',
        //     'enabled' => 1,
        //     'required' => 0,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page2->id,
        //     'key'=>'branch_primary_ruling'
        // ]);

        // FormPageItem::create([
        //     'label' => 'العام الهجرى',
        //     'type' => 'text',
        //     'enabled' => 1,
        //     'required' => 0,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page2->id,
        //     'key'=>'hijri_year_primary_ruling'
        // ]);

        // FormPageItem::create([
        //     'label' => 'التاريخ',
        //     'type' => 'text',
        //     'enabled' => 1,
        //     'required' => 0,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page2->id,
        //     'key'=>'date_primary_ruling'
        // ]);

        // FormPageItem::create([
        //     'label' => 'حكم الاستئناف',
        //     'type' => 'label',
        //     'enabled' => 1,
        //     'required' => 0,
        //     'website_view' => 1,
        //     'notes' => 'right',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'text',
        //     'height' => '115px',
        //     'length' => '',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page2->id,
        //     'key'=>''
        // ]);

        // FormPageItem::create([
        //     'label' => 'الدائرة',
        //     'type' => 'text',
        //     'enabled' => 1,
        //     'required' => 0,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page2->id,
        //     'key'=>'court_appeal_ruling'
        // ]);

        // FormPageItem::create([
        //     'label' => 'محكمة الستئناف الادارية',
        //     'type' => 'text',
        //     'enabled' => 1,
        //     'required' => 0,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page2->id,
        //     'key'=>'branch_appeal_ruling'
        // ]);

        // FormPageItem::create([
        //     'label' => 'العام الهجرى',
        //     'type' => 'text',
        //     'enabled' => 1,
        //     'required' => 0,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page2->id,
        //     'key'=>'hijri_year_appeal_ruling'
        // ]);

        // FormPageItem::create([
        //     'label' => 'التاريخ',
        //     'type' => 'text',
        //     'enabled' => 1,
        //     'required' => 0,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page2->id,
        //     'key'=>'date_appeal_ruling'
        // ]);

        // FormPageItem::create([
        //     'label' => 'نص الحكم الابتدائى',
        //     'type' => 'textarea',
        //     'enabled' => 1,
        //     'required' => 0,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page2->id,
        //     'key'=>'primary_ruling_text'
        // ]);

        // FormPageItem::create([
        //     'label' => 'أسباب الاعتراض',
        //     'type' => 'textarea',
        //     'enabled' => 1,
        //     'required' => 0,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page2->id,
        //     'key'=>'reasons'
        // ]);

        // FormPageItem::create([
        //     'label' => 'طلبات صندوق التنمية العقارية',
        //     'type' => 'textarea',
        //     'enabled' => 1,
        //     'required' => 0,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page2->id,
        //     'key'=>'redf_requests'
        // ]);



        // // third model
        // FormPageItem::create([
        //     'label' => 'المحكمة',
        //     'type' => 'text',
        //     'enabled' => 1,
        //     'required' => 1,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '20',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page3->id,
        //     'key'=>'petition_court'
        // ]);

        // FormPageItem::create([
        //     'label' => 'المحكمه الاداريه',
        //     'type' => 'text',
        //     'enabled' => 1,
        //     'required' => 1,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '20',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page3->id,
        //     'key'=>'petition_administrative_court'
        // ]);



        // FormPageItem::create([
        //     'label' => 'العام الهجري',
        //     'type' => 'text',
        //     'enabled' => 1,
        //     'required' => 1,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page3->id,
        //     'key'=>'petition_hijri_year'
        // ]);

        // FormPageItem::create([
        //     'label' => 'فرع صندوق التنميه العقاريه',
        //     'type' => 'text',
        //     'enabled' => 1,
        //     'required' => 1,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page3->id,
        //     'key'=>'petition_real_estate_development_fund_branch'
        // ]);



        // FormPageItem::create([
        //     'label' => 'القاضي',
        //     'type' => 'text',
        //     'enabled' => 1,
        //     'required' => 1,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '500',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page3->id,
        //     'key'=>'petition_judge'
        // ]);

        // FormPageItem::create([
        //     'label' => 'اسباب الأستئناف',
        //     'type' => 'textarea',
        //     'enabled' => 1,
        //     'required' => 1,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'textarea',
        //     'height' => '',
        //     'length' => '500',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page3->id,
        //     'key'=>'petition_reasons_for_appeal'
        // ]);

        // FormPageItem::create([
        //     'label' => 'المدعي قام ب',
        //     'type' => 'textarea',
        //     'enabled' => 1,
        //     'required' => 1,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '500',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page3->id,
        //     'key'=>'petition_the_plaintiff'
        // ]);

        // FormPageItem::create([
        //     'label' => 'يود الصندوق الاشاره ب',
        //     'type' => 'textarea',
        //     'enabled' => 1,
        //     'required' => 1,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'textarea',
        //     'height' => '',
        //     'length' => '500',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page3->id,
        //     'key'=>'petition_fund_would_like_to_point_out'
        // ]);

        // FormPageItem::create([
        //     'label' => 'الحكم',
        //     'type' => 'textarea',
        //     'enabled' => 1,
        //     'required' => 1,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'textarea',
        //     'height' => '',
        //     'length' => '500',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page3->id,
        //     'key'=>'petition_Judgment'
        // ]);

        // FormPageItem::create([
        //     'label' => 'الرد علي الحكم',
        //     'type' => 'textarea',
        //     'enabled' => 1,
        //     'required' => 1,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'textarea',
        //     'height' => '',
        //     'length' => '500',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page3->id,
        //     'key'=>'petition_response_to_the_ruling'
        // ]);




        // // four model
        // FormPageItem::create([
        //     'label' => 'اسم المواطن',
        //     'type' => 'text',
        //     'enabled' => 1,
        //     'required' => 1,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '500',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page4->id,
        //     'key'=>'letter_citizen_name'
        // ]);


        // FormPageItem::create([
        //     'label' => 'الدائرة',
        //     'type' => 'text',
        //     'enabled' => 1,
        //     'required' => 1,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-3',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '20',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page4->id,
        //     'key'=> 'letter_ring_of_the_administrative_court'
        // ]);

        // FormPageItem::create([
        //     'label' => 'الاختصاص',
        //     'type' => 'text',
        //     'enabled' => 1,
        //     'required' => 1,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-3',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '20',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page4->id,
        //     'key'=>'letter_jurisdiction_of_the_administrative_court'
        // ]);

        // FormPageItem::create([
        //     'label' => 'المحكمه الاداريه',
        //     'type' => 'text',
        //     'enabled' => 1,
        //     'required' => 1,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-3',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page4->id,
        //     'key'=>'letter_administrative_court'
        // ]);


        // FormPageItem::create([
        //     'label' => 'العام الهجري',
        //     'type' => 'text',
        //     'enabled' => 1,
        //     'required' => 1,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page4->id,
        //     'key'=>'letter_hijri_year'
        // ]);

        // FormPageItem::create([
        //     'label' => 'مقامه من',
        //     'type' => 'text',
        //     'enabled' => 1,
        //     'required' => 1,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '500',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page4->id,
        //     'key'=>'letter_position_from'
        // ]);

        // FormPageItem::create([
        //     'label' => 'رقم هويه المدعي',
        //     'type' => 'text',
        //     'enabled' => 1,
        //     'required' => 1,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '500',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page4->id,
        //     'key'=>'letter_plaintiff_id_number'
        // ]);

        // FormPageItem::create([
        //     'label' => 'القاضي ب',
        //     'type' => 'text',
        //     'enabled' => 1,
        //     'required' => 1,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '500',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page4->id,
        //     'key'=>'letter_judge'
        // ]);

        // FormPageItem::create([
        //     'label' => 'الدائرة',
        //     'type' => 'text',
        //     'enabled' => 1,
        //     'required' => 1,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-3',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '20',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page4->id,
        //     'key'=>'letter_circuit_court_of_appeals'
        // ]);

        // FormPageItem::create([
        //     'label' => 'الاختصاص',
        //     'type' => 'text',
        //     'enabled' => 1,
        //     'required' => 1,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-3',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '20',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page4->id,
        //     'key'=>'letter_jurisdiction_court_of_appeals'
        // ]);

        // FormPageItem::create([
        //     'label' => 'محكمه الاستئناف الاداريه',
        //     'type' => 'text',
        //     'enabled' => 1,
        //     'required' => 1,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-3',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '500',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page4->id,
        //     'key'=>'letter_administrative_court_of_appeal'
        // ]);

        // FormPageItem::create([
        //     'label' => 'رقم الحكم',
        //     'type' => 'text',
        //     'enabled' => 1,
        //     'required' => 1,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '500',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page4->id,
        //     'key'=>'letter_development_fund_confirms'
        // ]);

        // FormPageItem::create([
        //     'label' => 'العام الهجري',
        //     'type' => 'text',
        //     'enabled' => 1,
        //     'required' => 1,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '500',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page4->id,
        //     'key'=>'letter_development_fund_confirms'
        // ]);


        // // five model
        // FormPageItem::create([
        //     'label' => 'رقم السجل لمدني / التجاري',
        //     'type' => 'text',
        //     'enabled' => 1,
        //     'required' => 1,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '20',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page5->id,
        //     'key'=>'litigation_civil_or_commercial_registry'
        // ]);

        // FormPageItem::create([
        //     'label' => 'المحكمه الاداريه',
        //     'type' => 'text',
        //     'enabled' => 1,
        //     'required' => 1,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page5->id,
        //     'key'=>'litigation_administrative_court'
        // ]);

        // FormPageItem::create([
        //     'label' => 'البيان',
        //     'type' => 'textarea',
        //     'enabled' => 1,
        //     'required' => 1,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'textarea',
        //     'height' => '',
        //     'length' => '',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page5->id,
        //     'key'=>'litigation_statement'
        // ]);

        // FormPageItem::create([
        //     'label' => 'رقم الخطاب',
        //     'type' => 'text',
        //     'enabled' => 1,
        //     'required' => 1,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-4',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '500',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page5->id,
        //     'key'=>'litigation_letter_number'
        // ]);

        // FormPageItem::create([
        //     'label' => 'التاريخ',
        //     'type' => 'text',
        //     'enabled' => 1,
        //     'required' => 1,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '500',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page5->id,
        //     'key'=>'litigation_date'
        // ]);

        // // six model
        // FormPageItem::create([
        //     'label' => 'التاريخ',
        //     'type' => 'text',
        //     'enabled' => 1,
        //     'required' => 1,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '20',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page6->id,
        //     'key'=>'defense_date'
        // ]);
        //  FormPageItem::create([
        //     'label' => 'رقم الهويه الوطنيه',
        //     'type' => 'text',
        //     'enabled' => 1,
        //     'required' => 1,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page6->id,
        //     'key'=>'defense_national_identification_number'
        // ]);

        // FormPageItem::create([
        //     'label' => 'المحكمه الاداريه',
        //     'type' => 'text',
        //     'enabled' => 1,
        //     'required' => 1,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page5->id,
        //     'key'=>'defense_administrative_court'
        // ]);

        // FormPageItem::create([
        //     'label' => 'نفيد فضيلتكم بان',
        //     'type' => 'textarea',
        //     'enabled' => 1,
        //     'required' => 1,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-4',
        //     'input_type' => 'textarea',
        //     'height' => '',
        //     'length' => '500',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page5->id,
        //     'key'=>'defense_we_inform_you_that'
        // ]);

        // FormPageItem::create([
        //     'label' => 'بالاضافه',
        //     'type' => 'textarea',
        //     'enabled' => 1,
        //     'required' => 1,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'textarea',
        //     'height' => '',
        //     'length' => '500',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page5->id,
        //     'key'=>'defense_in_addition'
        // ]);

    }
}
