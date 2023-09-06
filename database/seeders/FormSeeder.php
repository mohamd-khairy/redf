<?php

namespace Database\Seeders;

use App\Enums\FormEnum;
use App\Models\Form;
use App\Models\FormPage;
use Illuminate\Database\Seeder;
use App\Models\FormPageItem;

class FormSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        $data = json_encode([
            [
                'text' => 'سري للغايه',
            ],
            [
                'text' => 'سري',
            ],
            [
                'text' => 'مؤتمن',
            ],
            [
                'text' => 'عادي',
            ],
        ]);

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
        //template
        $form1 = Form::firstOrCreate([
            'id' => FormEnum::CASE,
            'name' => 'استمارة التقاضي والطلب',
            'description' => 'استمارة التقاضي والطلب',
            'user_id' => 1,
            'main' => 1,
            'template_id' => 1,
        ]);
        $form2 = Form::firstOrCreate([
            'id' => FormEnum::LEGAL_ADVICE,
            'name' => 'طلب استمارة استشارة قانونية',
            'description' => 'طلب استمارة استشارة قانونية',
            'user_id' => 1,
            'main' => 1,
            'template_id' => 2,
        ]);
        $form3 = Form::firstOrCreate([
            'id' => FormEnum::LEGAL_REVIEW,
            'name' => 'استمارة مراجعة العقد',
            'description' => 'استمارة مراجعة العقد',
            'user_id' => 1,
            'main' => 1,
            'template_id' => 3,
        ]);
        // pages
        $form_page1 = FormPage::create([
            'title' => ' طلب قضية',
            'form_id' => $form1->id
        ]);
        $form_page2 = FormPage::create([
            'title' => 'طلب إستشارة ',
            'form_id' => $form2->id
        ]);
        $form_page3 = FormPage::create([
            'title' => 'طلب مراجعة العقد',
            'form_id' => $form3->id
        ]);

        //
        // FormPageItem::create([
        //     'label' => ' المحكمة (الاختصاص)',
        //     'type' => 'select',
        //     'enabled' => 1,
        //     'required' => 1,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '20',
        //     'childList' => $courts,
        //     'form_page_id' => $form_page1->id
        // ]);
        // FormPageItem::create([
        //     'label' => 'اسم الطالب',
        //     'type' => 'text',
        //     'enabled' => 1,
        //     'required' => 1,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-4',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '100',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page1->id
        // ]);
        // FormPageItem::create([
        //     'label' => 'هوية الموظف',
        //     'type' => 'text',
        //     'enabled' => 1,
        //     'required' => 1,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '100',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page1->id
        // ]);
        // FormPageItem::create([
        //     'label' => 'قسم',
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
        //     'form_page_id' => $form_page1->id
        // ]);
        // FormPageItem::create([
        //     'label' => 'الموضوع',
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
        //     'form_page_id' => $form_page1->id
        // ]);
        // FormPageItem::create([
        //     'label' => 'الغرض من التقاضي والطلب',
        //     'type' => 'textarea',
        //     'enabled' => 1,
        //     'required' => 1,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '1000',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page1->id
        // ]);
        // FormPageItem::create([
        //     'label' => 'تفاصيل الدعوى / التقاضي',
        //     'type' => 'textarea',
        //     'enabled' => 1,
        //     'required' => 1,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '1000',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page1->id
        // ]);
        // FormPageItem::create([
        //     'label' => 'الملفات المرفقة تدعم الطلب ',
        //     'type' => 'file',
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
        //     'form_page_id' => $form_page1->id
        // ]);
        // FormPageItem::create([
        //     'label' => 'موعد الجلسة إن وجدت ',
        //     'type' => 'text',
        //     'enabled' => 1,
        //     'required' => 0,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '20',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page1->id
        // ]);
        // FormPageItem::create([
        //     'label' => 'التقاضي والمرافعة السرية',
        //     'type' => 'select',
        //     'enabled' => 1,
        //     'required' => 1,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '20',
        //     'childList' => $data,
        //     'form_page_id' => $form_page1->id
        // ]);
        // FormPageItem::create([
        //     'label' => 'ملاحظات ',
        //     'type' => 'textarea',
        //     'enabled' => 1,
        //     'required' => 0,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '1000',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page1->id
        // ]);

        //        
        // FormPageItem::create([
        //     'label' => 'اسم الطالب',
        //     'type' => 'text',
        //     'enabled' => 1,
        //     'required' => 1,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '200',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page2->id
        // ]);
        // FormPageItem::create([
        //     'label' => 'هوية الموظف',
        //     'type' => 'text',
        //     'enabled' => 1,
        //     'required' => 1,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '200',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page2->id
        // ]);
        // FormPageItem::create([
        //     'label' => 'قسم',
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
        //     'form_page_id' => $form_page2->id
        // ]);
        FormPageItem::create([
            'label' => 'موضوع الاستشارة القانونية',
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
            'form_page_id' => $form_page2->id
        ]);
        // FormPageItem::create([
        //     'label' => 'وصف الاستشارة القانونية',
        //     'type' => 'textarea',
        //     'enabled' => 1,
        //     'required' => 0,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '500',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page2->id
        // ]);
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
            'form_page_id' => $form_page2->id
        ]);
        FormPageItem::create([
            'label' => 'الغرض من الاستشارة القانونية',
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
            'form_page_id' => $form_page2->id
        ]);
        FormPageItem::create([
            'label' => 'استشارة قانونية سرية',
            'type' => 'select',
            'enabled' => 1,
            'required' => 0,
            'website_view' => 1,
            'notes' => '',
            'comment' => '',
            'width' => 'col-4',
            'input_type' => 'text',
            'height' => 'auto',
            'childList' => $data,
            'form_page_id' => $form_page2->id
        ]);
        FormPageItem::create([
            'label' => 'ملحوظات',
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
            'form_page_id' => $form_page2->id
        ]);

        //
        // FormPageItem::create([
        //     'label' => 'اسم الطالب ',
        //     'type' => 'text',
        //     'enabled' => 1,
        //     'required' => 1,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '100',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page3->id
        // ]);
        // FormPageItem::create([
        //     'label' => 'هويه الموظف',
        //     'type' => 'text',
        //     'enabled' => 1,
        //     'required' => 1,
        //     'website_view' => 1,
        //     'notes' => '',
        //     'comment' => '',
        //     'width' => 'col-12',
        //     'input_type' => 'text',
        //     'height' => '',
        //     'length' => '100',
        //     'childList' => json_encode([]),
        //     'form_page_id' => $form_page3->id
        // ]);
        // FormPageItem::create([
        //     'label' => 'القسم ',
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
        //     'form_page_id' => $form_page3->id
        // ]);
        // FormPageItem::create([
        //     'label' => 'الموضوع',
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
        //     'form_page_id' => $form_page3->id
        // ]);
        // FormPageItem::create([
        //     'label' => 'تفاصيل العقد',
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
        //     'form_page_id' => $form_page3->id
        // ]);
        FormPageItem::create([
            'label' => 'الغرض من الطلب',
            'type' => 'radio',
            'enabled' => 1,
            'required' => 1,
            'website_view' => 1,
            'notes' => '',
            'comment' => '',
            'width' => 'col-12',
            'input_type' => 'text',
            'height' => '',
            'length' => '',
            'childList' => json_encode([
                [
                    'text' => 'مراجعة العقد',
                ],
                [
                    'text' => 'تجهيز العقد',
                ],
            ]),
            'form_page_id' => $form_page3->id
        ]);
        FormPageItem::create([
            'label' => 'ملف العقد المرفق',
            'type' => 'file',
            'enabled' => 1,
            'required' => 0,
            'website_view' => 1,
            'notes' => '',
            'comment' => '',
            'width' => 'col-12',
            'input_type' => 'text',
            'height' => '',
            'length' => '',
            'childList' => json_encode([]),
            'form_page_id' => $form_page3->id
        ]);
        FormPageItem::create([
            'label' => 'ملاحظات',
            'type' => 'textarea',
            'enabled' => 1,
            'required' => 0,
            'website_view' => 1,
            'notes' => '',
            'comment' => '',
            'width' => 'col-12',
            'input_type' => 'text',
            'height' => '',
            'length' => '1000',
            'childList' => json_encode([]),
            'form_page_id' => $form_page3->id
        ]);
    }
}
