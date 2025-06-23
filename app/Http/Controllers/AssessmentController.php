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
use App\Models\User;
use App\Models\Assessment;
use App\Models\Choice;
use App\Models\AnswerKey;
use App\Models\Question;

class AssessmentController extends Controller
{
    private $assessment_model;
    private $class_model;
    private $choice_model;
    private $question_model;
    private $answer_key_model;

    public function __construct(Assessment $assessment_model, Classes $class_model, Question $question_model, AnswerKey $answer_key_model, Choice $choice_model)
    {
        $this->assessment_model = $assessment_model;
        $this->class_model = $class_model;
        $this->question_model = $question_model;
        $this->answer_key_model = $answer_key_model;
        $this->choice_model = $choice_model;
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

        $classes = $this->class_model->all();

        if ($class_id != 0) {
            $encryptedClassId = $class_id;
            try {
                $class_id = Crypt::decrypt($class_id);
            } catch (DecryptException $e) {
                return redirect()->route('class.index')->withErrors([
                    'error' => 'Invalid class ID',
                ]);
            }

            return view('pages.classes.assessments.create', ['class_id' => $encryptedClassId, 'classes' => $classes]);
        }

        return view('pages.classes.assessments.create', ['class_id' => null, 'classes' => $classes]);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'assessment_date' => 'required|string',
            'assessment_time' => 'required|string',
            'type' => 'required|string',
            'total' => 'required|string',
            'name' => 'required|string',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }



        $assessment = $this->assessment_model->create([
            'class_id' => $request->class_id,
            'name' => $request->name,
            'type' => $request->type,
            'total' => (int) $request->total,
            'assessment_time' => $request->assessment_time,
            'assessment_date' => $request->assessment_date,
        ]);

        $questions = $request->question;

        for ($i = 0; $i < count($questions); $i++) {
            $q_name = $questions[$i];

            $question = $this->question_model->create([
                'q_name' => $q_name,
                'type' => $request->type,
                'assessment_id' => $assessment->id
            ]);

            $choices = $request->input('choices_' . $i);
            $correct_answer = $request->input('correct_' . $i);

            foreach ($choices as $choice_text) {
                $choice = $this->choice_model->create([
                    'choices' => $choice_text,
                    'question_id' => $question->id,
                ]);

                if ($choice_text == $correct_answer) {
                    $this->answer_key_model->create([
                        'choice_id' => $choice->id,
                        'question_id' => $question->id,
                        'answer' => $choice_text
                    ]);
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Successfully created assessment"
        ]);
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
