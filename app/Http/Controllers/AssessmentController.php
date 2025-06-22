<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use App\Services\UserRestrictions;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Mail\enrollment_notification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

use App\Models\Classes;
use App\Models\CourseModel;
use App\Models\ClassUser;
use App\Models\User;
use App\Models\Announcement;
use App\Models\Assessment;

class AssessmentController extends Controller
{
    private $assessment_model;
    private $class_model;

    public function __construct(Assessment $assessment_model, Classes $class_model){
        $this->assessment_model = $assessment_model;
        $this->class_model = $class_model;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->assessment_model->with('class')->orderBy('created_at', 'desc')
                ->get();

            return DataTables::of($data)
                ->addColumn('course', function ($row) {
                    return $row->class ? $row->class->course_name : 'N/A';
                })

                ->addColumn('action', function ($row) {
                    $viewBtn = '<a href= " ' . route('assessment.show', ['assessment_id' => $row->id]) . ' " class="btn btn-sm btn-primary mb-2" data-bs-toggle="tooltip" title="take tis quiz">Take ' . ucfirst($row->type) . ' </a>';
                    // $editBtn = '<a href= " ' . route('user.edit', ['userId' => $row->id]) . ' " class="btn btn-sm btn-warning w-100" data-bs-toggle="tooltip" title="Edit this user"><i class="fa-solid fa-user-pen"></i></a>';
                    // return $viewBtn . ' ' . $editBtn;
                    return $viewBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('pages.classes.assessments.index');
    }

    public function show($assessment_id)
    {
        $assessment = $this->assessment_model->with([
            'question.choices.answer_keys'
        ])->findOrFail($assessment_id);

        return view('pages.classes.assessments.show', compact('assessment'));
    }

    public function create($class_id)
    {

        $assessments = $this->class_model->all();

        if($class_id != 0){
            $encryptedClassId = $class_id;
            try {
                $class_id = Crypt::decrypt($class_id);
            } catch (DecryptException $e) {
                return redirect()->route('class.index')->withErrors([
                    'error' => 'Invalid class ID',
                ]);
            }

            return view('pages.classes.assessments.create', ['class_id' => $encryptedClassId, 'assessments' => $assessments]);
        }


        return view('pages.classes.assessments.create', ['class_id' => null, 'assessments' => $assessments]);
    }

    public function store(Request $request)
    {
        // Logic to store a new assessment
    }

    public function edit($id)
    {
        // Logic to show form for editing an existing assessment
    }

    public function update(Request $request, $id)
    {
        // Logic to update an existing assessment
    }

    public function destroy($id)
    {
        // Logic to delete an assessment
    }
}
