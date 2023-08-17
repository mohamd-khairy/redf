<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Rules\DifferentUsers;
use App\Models\FormRequestSide;
use App\Http\Controllers\Controller;

class FormRequestSideController extends Controller
{
    public function form_request_side(Request $request){
        $validatedData = $request->validate([
            'form_request_id' => 'required|exists:form_requests,id',
            'claimant_id' => ['required', 'exists:users,id'],
            'defendant_id' => ['required', 'exists:users,id'],
        ]);

        $formRequestSide = FormRequestSide::create($validatedData);

        return responseSuccess($formRequestSide, 'Form Request Side has been successfully Created');

    }
}
