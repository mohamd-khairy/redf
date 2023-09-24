<?php

namespace App\Http\Controllers\Api;

use App\Models\File;
use App\Models\Treatment;
use App\Filters\SortFilters;
use Illuminate\Http\Request;
use App\Filters\SearchFilters;
use App\Models\TreatmentAction;
use App\Services\UploadService;
use App\Enums\TreatmentTypeEnum;
use Illuminate\Pipeline\Pipeline;
use App\Http\Requests\PageRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\TreatmentRequest;
use App\Http\Requests\CreateTreatmentActionRequest;
use App\Http\Requests\AssignUsersToTreatmentRequest;
use Throwable;

class TreatmentController extends Controller
{
    public $model = Treatment::class;

    public function __construct()
    {
        $this->middleware(['auth']);
        // $this->middleware('permission:read-organization', ['only' => ['index', 'show']]);
        // $this->middleware('permission:create-organization', ['only' => ['store']]);
        // $this->middleware('permission:update-organization', ['only' => ['update']]);
        // $this->middleware('permission:delete-organization', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PageRequest $request)
    {
        $treatments = Treatment::with('department:id,name');
        $data = app(Pipeline::class)->send($treatments)->through([
            SearchFilters::class,
            SortFilters::class,
        ])->thenReturn();

        $data = request('pageSize') == -1 ?  $data->get() : $data->paginate(request('pageSize', 15));

        return responseSuccess(['treatments' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TreatmentRequest $request)
    {
        try {
            $validatedData = $request->validated();
            if (!isset($validatedData['type'])) {
                $validatedData['type'] = TreatmentTypeEnum::CONSULTATION;
            }
            // Create the Treatment
            $treatment = Treatment::create($validatedData);

            return responseSuccess($treatment, 'Treatment has been successfully created');
        } catch (\Throwable $e) {
            return responseFail($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TreatmentRequest  $request, $id)
    {
        try {
            // Find the treatment by ID
            $treatment = Treatment::findOrFail($id);

            $validatedData = $request->validated();

            // Check if 'type' is not provided in the request, then set it to 'consultant'
            if (!isset($validatedData['type'])) {
                $validatedData['type'] = treatmentTypeEnum::CONSULTATION;
            }

            // Update the treatment
            $treatment->update($validatedData);

            return responseSuccess($treatment, 'treatment has been successfully updated');
        } catch (\Throwable $e) {
            return responseFail($e->getMessage());
        }
    }

    public function show($id)
    {
        $treatment = Treatment::with('files', 'treatmentInformation', 'treatmentAction', 'user:id,name,avatar', 'department:id,name')->findOrFail($id);

        return responseSuccess($treatment, 'Treatment has been successfully showed');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $treatment = Treatment::findOrFail($id);
        // Delete related files
        $treatment->files()->delete();

        // Delete related treatment information
        $treatment->treatmentInformation()->delete();

        // Delete related treatment actions
        $treatment->treatmentAction()->delete();

        // Finally, delete the treatment itself
        $treatment->delete();

        return responseSuccess([], 'Treatment has been successfully deleted');
    }

    public function uploadFiles(Request $request)
    {
        $treatment = Treatment::with('files')->find($request->treatment_id);
        if ($request->hasFile('files')) {
            $files = $request->file('files');

            foreach ($files as $file) {
                $filename = $file->getClientOriginalName();
                $filePath = UploadService::store($file, 'treatments');
                // Create a new file record for each uploaded file
                $fileRecord = new File([
                    'name' => $filename,
                    'path' => $filePath,
                    'user_id' => auth()->id(),
                    'start_date' => now(),
                    'type' => 'treatment',
                    'priority' => 'high'
                ]);
                $fileRecord->fileable()->associate($treatment); // Associate the file with the treatment
                $fileRecord->save();

                return responseSuccess($treatment, 'Files has been successfully Uploaded');
            }
        }
    }

    public function assignUser(AssignUsersToTreatmentRequest $request)
    {
        try {

            $treatment = Treatment::findOrFail($request->treatment_id );
            $userIds = $request->user_ids;
            $date = $request->date;

            // Detach all users from the treatment
            $treatment->users()->detach();
            // Attach multiple users to the treatment with the specified date
            foreach ($userIds as $userId) {
                $treatment->users()->attach($userId, ['date' => $date]);
            }
            return responseSuccess([], 'Users assigned to treatment successfully');
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function treatmentActions(CreateTreatmentActionRequest  $request)
    {
        try {
            // Create a new treatment action
            $treatmentAction = TreatmentAction::create($request->validated());
            return responseSuccess($treatmentAction, 'Treatment action has been successfully created');
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
