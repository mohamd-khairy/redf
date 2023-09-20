<?php

namespace App\Services;

use App\Enums\FormEnum;
use App\Models\Form;
use App\Models\FormPage;
use App\Models\FormPageItem;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\FormItemResource;

class FormService
{
    public function getAllForms()
    {
        return Form::whereIn('id', [
            FormEnum::DEFENCE_CASE_FORM, FormEnum::CLAIM_CASE_FORM, FormEnum::RESUME_CASE_FORM,
            FormEnum::SOLICITATION_CASE_FORM, FormEnum::OBJECTION_CASE_FORM, FormEnum::IMPLEMENTATION_CASE_FORM
        ])->with('template')->paginate(15);
    }

    public function createForm($data)
    {
        $data['user_id'] = auth()->user()->id;
        $form = Form::firstOrCreate($data);
        return $form;
    }

    public function updateForm($id, $request)
    {
        $form = Form::find($id);
        if (!$form) {
            return responseFail('there is no form with this id');
        }
        $data = $this->updateData($request, $form);
        return $form;
    }

    public function updateData($request, $form)
    {
        // Delete old form pages and their items
        $form->pages()->each(function ($page) {
            $page->items()->delete();
            // $page->delete();
        });
        // Create new form pages with new elements
        $pagesData = $request->input('pages');
        foreach ($pagesData as $pageData) {

            // $page = new FormPage(['title' => $pageData['title']['title'], 'editable' =>  $pageData['title']['editing'] == false ? 0 : 1]);
            // $form->pages()->save($page);

            $page = FormPage::updateOrCreate(['form_id' => $form->id, 'title' => trim($pageData['title']['title'])]);

            if (isset($pageData['items']) && is_array($pageData['items'])) {
                foreach ($pageData['items'] as $itemData) {
                    if (!isset($itemData['removed']) || !$itemData['removed']) {
                        $item = new FormPageItem(collect($itemData)->only(['type', 'label', 'notes', 'width', 'height', 'enabled', 'required', 'website_view', 'childList'])->toArray());
                        $page->items()->save($item);
                    }
                }
            }
        }
        return $form->refresh();
    }

    public function updateFormBasic($id, $request)
    {
        $form = Form::find($id);
        if (!$form) {
            return responseFail('there is no form with this id');
        }
        $data = $form->update($request->only('name', 'description'));
        return $form;
    }
}
