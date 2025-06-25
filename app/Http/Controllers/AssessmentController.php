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
                    //
                    $viewBtn = '<a href= " ' . route('assessment.show', ['assessment_id' => Crypt::encrypt($row->id)]) . ' " class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="view"><i class="fa-solid fa-eye"></i></a>';
                    $editBtn = '<a href= " ' . route('assessment.edit', ['assessment_id' => Crypt::encrypt($row->id)]) . ' " class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="edit"><i class="fa-solid fa-pen-to-square"></i></a>';
                    $deleteBtn = '<a href= "#" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="delete"><i class="fa-solid fa-trash" title="Remove question" onclick = "deleteAssessments(\'' . Crypt::encrypt($row->id) . '\')"></i></a>';
                    return $viewBtn . ' ' . $editBtn . ' ' . $deleteBtn;
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
        $assessment_time = '';
        $questions = $request->question;
        $meridiems = [
            1 => ($request->hrs > 1) ? 'hrs' : 'hr',
            2 => ($request->minutes > 1) ? 'mins' : 'min',
        ];

        $validator = Validator::make($request->all(), [
            'assessment_date' => 'required|string',
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

        if ($request->total == 0) {
            return response()->json([
                'success' => false,
                'message' => "Question must not be empty"
            ]);
        }

        foreach ($questions as $question) {
            if ($question == '') {
                return response()->json([
                    'success' => false,
                    'message' => "An error occurred because one or more required question fields are empty."
                ]);
            }
        }

        foreach ($questions as $index => $question) {
            $correct_answer = $request->input('correct_' . $index);
            if ($correct_answer == '') {
                return response()->json([
                    'success' => false,
                    'message' => "Correct Answer field is required"
                ]);
            }
        }

        if ($request->hrs == 00 && $request->minutes == 00) {
            return response()->json([
                'success' => false,
                'message' => "Time duration field is required"
            ]);
        } elseif ($request->hrs == 00 && $request->minutes != 00) {
            $assessment_time = $request->minutes . ' ' . $meridiems[2];
        } elseif ($request->hrs != 00 && $request->minutes == 00) {
            $assessment_time = $request->hrs . ' ' . $meridiems[1];
        } else {
            $assessment_time = $request->hrs . ' ' . $meridiems[1] . ' and ' . $request->minutes . ' ' . $meridiems[2];
        }

        $assessment = $this->assessment_model->create([
            'class_id' => $request->class_id,
            'name' => $request->name,
            'type' => $request->type,
            'total' => (int) $request->total,
            'assessment_time' => $assessment_time,
            'assessment_date' => $request->assessment_date,
        ]);

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

    public function edit($assessment_id)
    {
        $encryptedClassId = $assessment_id;

        try {
            $assessment_id = Crypt::decrypt($assessment_id);
        } catch (DecryptException $e) {
            return redirect()->route('class.index')->withErrors([
                'error' => 'Invalid class ID',
            ]);
        }

        $assessment = $this->assessment_model->with(['class', 'question.choices.answer_keys'])->find($assessment_id);
        $timeArray = $assessment->assessment_time_array;
        $classes = $this->class_model->all();

        if (!$assessment) {
            return redirect()->route('user.dashboard')->withErrors([
                'error' => 'Assessment do not exist'
            ]);
        }

        return view('pages.classes.assessments.edit_update', ['assessments' => $assessment ?? null, 'classes' => $classes ?? null, 'time' => $timeArray]);
    }

    public function update(Request $request)
    {
        return response()->json([
            'message' => $request->all()
        ]);
    }

    public function destroyQuestion(Request $request)
    {
        $res = $this->question_model->find($request->question_id);
        $assessment = $res->assessment;

        $assessment = $this->assessment_model->find($request->assessment_id);

        if ($assessment->total == 1) {
            return response()->json([
                'success' => false,
                'message' => "You cannot delete this question. Assessment should have atlease one question remain"
            ]);
        }

        $assessment = $assessment->update([
            'total' => $assessment->total - 1
        ]);

        if (!$res) {
            return response()->json([
                'success' => false,
                'message' => "Question id do not exists"
            ]);
        }

        $res = $res->delete();

        if (!$res) {
            return response()->json([
                'success' => false,
                'message' => "Failed to remove question"
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => "Successfully remove question"
        ]);
    }


    function destroy($assessment_id)
    {
        try {
            $assessment_id = Crypt::decrypt($assessment_id);
        } catch (DecryptException $e) {
            return response()->json([
                'success' => false,
                'message' => "Assessment id do not exist"
            ]);
        }

        $assessment = $this->assessment_model->find($assessment_id);
        $res = $assessment->delete();

        if (!$res) {
            return response()->json([
                'success' => false,
                'message' => "Failed to delete assessment"
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => "Successfully deleted assessment"
        ]);
    }
}
