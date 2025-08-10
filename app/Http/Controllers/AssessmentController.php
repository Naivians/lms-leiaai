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
use App\Models\AssessmentProgress;
use App\Models\ProgressDetail;

use function Laravel\Prompts\progress;

class AssessmentController extends Controller
{
    private $assessment_model;
    private $class_model;
    private $choice_model;
    private $question_model;
    private $assessment_progress_model;
    private $assessment_progress_details_model;
    private $answer_key_model;

    public function __construct(Assessment $assessment_model, Classes $class_model, Question $question_model, AnswerKey $answer_key_model, Choice $choice_model, AssessmentProgress $assessment_progress_model, ProgressDetail $assessment_progress_details_model)
    {
        $this->assessment_model = $assessment_model;
        $this->class_model = $class_model;
        $this->question_model = $question_model;
        $this->answer_key_model = $answer_key_model;
        $this->choice_model = $choice_model;
        $this->assessment_progress_model = $assessment_progress_model;
        $this->assessment_progress_details_model = $assessment_progress_details_model;
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
                    $editBtn = '<a href= " ' . route('assessment.edit', ['assessment_id' => Crypt::encrypt($row->id)]) . ' " class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="view"><i class="fa-solid fa-eye"></i></a>';
                    $deleteBtn = '<a href= "#" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="delete"><i class="fa-solid fa-trash" title="Remove question" onclick = "deleteAssessments(\'' . Crypt::encrypt($row->id) . '\')"></i></a>';
                    return $editBtn . ' ' . $deleteBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('pages.classes.assessments.index');
    }

    public function show($assessment_id)
    {

        $encryptedClassId = $assessment_id;

        try {
            $assessment_id = Crypt::decrypt($assessment_id);
        } catch (DecryptException $e) {
            return redirect()->route('class.index')->withErrors([
                'error' => 'Invalid class ID',
            ]);
        }
        $assessment = $this->assessment_model->find($assessment_id);
        $timeArray = $assessment->assessment_time_array;
        return view('pages.classes.assessments.assessment_intro', compact('assessment'));
    }

    public function takeAssessment(Request $request, $assessment_id)
    {
        try {
            $decryptedId = Crypt::decrypt($assessment_id);
        } catch (DecryptException $e) {
            return redirect()->route('class.index')->withErrors(['error' => 'Invalid class ID']);
        }

        $assessment = $this->assessment_model
            ->select('id', 'total', 'type', 'assessment_date', 'assessment_time', 'name')
            ->findOrFail($decryptedId);

        $sessionKey = "quiz_progress_{$decryptedId}";

        // if (session()->has($sessionKey)) {
        //     $progress = session($sessionKey);
        //     if ($progress['current_page'] > $assessment->total) {
        //         session()->forget($sessionKey);
        //         return redirect()->route('assessment.complete', ['assessment_id' => $assessment_id]);
        //     }
        // }

        if (!session()->has($sessionKey)) {
            session()->put($sessionKey, [
                'current_page' => 1,
                'start_time'   => now(),
            ]);
        }

        $progress = session($sessionKey);

        $requestedPage = (int) $request->query('page', 1);
        if ($requestedPage !== $progress['current_page']) {
            return redirect()->route('assessment.take', [
                'assessment_id' => $assessment_id,
                'page'          => $progress['current_page']
            ]);
        }

        $questions = $this->question_model
            ->where('assessment_id', $decryptedId)
            ->with('choices.answer_key')
            ->paginate(1, ['*'], 'page', $progress['current_page']);

        return view('pages.classes.assessments.take_assessment', compact('questions', 'assessment'));
    }

    public function answer(Request $request, $assessment_id)
    {

        try {
            $decryptedId = Crypt::decrypt($assessment_id);
        } catch (DecryptException $e) {
            return redirect()->route('class.index')->withErrors(['error' => 'Invalid class ID']);
        }

        $assessment = $this->assessment_model->findOrFail($decryptedId);


        $progressRecords = $this->assessment_progress_model->firstOrCreate(
            [
                'user_id'       => Auth::id(),
                'assessment_id' => $decryptedId
            ],
            [
                'user_id' => Auth::id(),
                'assessment_id' => $decryptedId,
                'name' => $assessment->name,
                'type' => $assessment->type,
                'total' => $assessment->total,
                'score' => 0,
                'status' => "In progress",
            ]
        );

        $sessionKey = "quiz_progress_{$decryptedId}";
        $progress   = session($sessionKey);

        // Increment to next question
        $progress['current_page']++;
        session()->put($sessionKey, $progress);

        $totalQuestions = $this->question_model::where('assessment_id', $decryptedId)->count();

        $this->assessment_progress_details_model->create([
            'progress_id' => $progressRecords->id,
            'qid' => $request->qid,
            'cid' => $request->cid,
        ]);

        session()->put('progress_id', $progressRecords->id);

        if ($progress['current_page'] > $totalQuestions) {
            session()->forget($sessionKey);
            return redirect()->route('assessment.complete', ['assessment_id' => $assessment_id]);
        }

        return redirect()->route('assessment.take', [
            'assessment_id' => $assessment_id,
            'page'          => $progress['current_page']
        ]);
    }

    public function complete($assessment_id)
    {
        try {
            $decryptedId = Crypt::decrypt($assessment_id);
        } catch (DecryptException $e) {
            return redirect()->route('class.index')->withErrors(['error' => 'Invalid class ID']);
        }

        $assessment = $this->assessment_model->select('total', 'class_id', 'name')->find($decryptedId);

        $class_id = Crypt::encrypt($assessment->class_id);

        if (!session()->has('progress_id')) {
            return redirect()->route('class.index')->withErrors(['error' => 'No progress found for this assessment.']);
        }

        $progressId = session('progress_id');

        // Step 1: Get all chosen answers for this progress
        $progress_detail = $this->assessment_progress_details_model
            ->where('progress_id', $progressId)
            ->get();

        // Step 2: Get answer keys that match chosen cids (correct answers)
        $answer_key = $this->answer_key_model
            ->whereIn('choice_id', $progress_detail->pluck('cid'))
            ->get();

        // Step 3: Count correct answers
        $correctCount = $answer_key->count();

        $scorePercentage = 0;
        $status = 'Failed';
        if ($correctCount > 0) {
            $scorePercentage = ($correctCount / $assessment->total) * 100;
        }

        // Step 4: Update score in assessment_progress table
        $this->assessment_progress_model
            ->where('id', $progressId)
            ->update([
                'score'  => $correctCount,
                'status' => $scorePercentage >= 75 ? 'Passed' : 'Failed',
            ]);

        $assessmentProgress = $this->assessment_progress_model->find($progressId);

        return view('pages.classes.assessments.completed', compact('class_id', 'decryptedId', 'assessmentProgress', 'assessment', ));
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

        foreach ($questions as $index => $question) {

            if ($question == '') {
                return response()->json([
                    'success' => false,
                    'message' => "An error occurred because one or more required question fields are empty."
                ]);
            }

            $correct_answer = $request->input('correct_' . $index);
            if ($correct_answer == '') {
                return response()->json([
                    'success' => false,
                    'message' => "Correct Answer field is required"
                ]);
            }

            if (!in_array($correct_answer, $request->input('choices_' . $index))) {
                return response()->json([
                    'success' => false,
                    'message' => "Answer key on question #" . ($index + 1) . " do not match with any choices"
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
        $validator = Validator::make($request->all(), [
            'assessment_id' => 'required|integer|exists:assessments,id',
            'assessment_date' => 'required|string',
            'name' => 'required|string',
            'type' => 'required|string',
            'hrs' => 'required|string',
            'minutes' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ]);
        }

        if ($request->hrs == 0 && $request->minutes == 0) {
            return response()->json([
                'success' => false,
                'message' => "Time duration field is required"
            ]);
        }

        $assessment_time = '';
        if ($request->hrs > 0) {
            $assessment_time .= $request->hrs . ' ' . ($request->hrs > 1 ? 'hrs' : 'hr');
        }
        if ($request->minutes > 0) {
            $assessment_time .= ($assessment_time ? ' and ' : '') . $request->minutes . ' ' . ($request->minutes > 1 ? 'mins' : 'min');
        }

        $assessment = $this->assessment_model->find($request->assessment_id);
        if (!$assessment) {
            return response()->json([
                'success' => false,
                'message' => 'Assessment ID does not exist.'
            ]);
        }

        $assessment->update([
            'name' => $request->name,
            'class_id' => $request->class_id,
            'type' => $request->type,
            'assessment_time' => $assessment_time,
            'assessment_date' => $request->assessment_date,
            'total' => $assessment->total,
            'is_published' => $request->is_published,
        ]);

        foreach ($request->question as $q_index => $questionText) {
            $questionId = $request->question_id[$q_index] ?? null;
            $correctId = $request->correct_id[$q_index] ?? null;
            $correctInput = $request->input("correct_{$q_index}");
            $choicesInput = $request->input("choices_{$q_index}") ?? [];
            $choicesIdList = $request->input("choices_id_{$q_index}") ?? [];
            if (empty($questionText)) {
                return response()->json(['success' => false, 'message' => "Question #" . ($q_index + 1) . " is required."]);
            }

            if (empty($correctInput)) {
                return response()->json(['success' => false, 'message' => "Correct answer for Question #" . ($q_index + 1) . " is required."]);
            }

            if (empty($choicesInput) || !is_array($choicesInput)) {
                return response()->json(['success' => false, 'message' => "Choices for Question #" . ($q_index + 1) . " are required."]);
            }

            if (!in_array($correctInput, $choicesInput)) {
                return response()->json(['success' => false, 'message' => "Correct answer for Question #" . ($q_index + 1) . " must match one of the choices."]);
            }

            $question = $this->question_model->find($questionId);
            $answerKey = $this->answer_key_model->find($correctId);

            if (!$question || !$answerKey) {
                return response()->json(['success' => false, 'message' => "Question or answer key not found for Question #" . ($q_index + 1)]);
            }

            $question->update([
                'q_name' => $questionText,
                'type' => $request->type,
            ]);

            foreach ($choicesInput as $c_index => $choiceText) {
                $choiceId = $choicesIdList[$c_index] ?? null;
                if (!$choiceId) continue;

                $choice = $this->choice_model->find($choiceId);

                if ($choice) {
                    $choice->update(['choices' => $choiceText]);
                }

                if ($choiceText == $correctInput) {
                    $answerKey->update([
                        'answer' => $correctInput,
                        'choice_id' => $choiceId
                    ]);
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Successfully updated assessment."
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

    public function saveAssessments(Request $request)
    {

        $assessment = $this->assessment_model->find($request->assessment_id);

        if (!$assessment) {
            return response()->json([
                'success' => false,
                "message" => "Assessment id do not exists"
            ]);
        }

        $totalQuestion = $assessment->total;
        $score = 0;
        $percentage = 0;
        $status = '';
        $statusText = '';

        $assessmentProgressData = [
            'user_id' => Auth::id(),
            'assessment_id' => $assessment->id,
            'name' => $assessment->name,
            'type' => $assessment->type,
            'total' => $assessment->total,
            'score' => 0,
            'status' => "Not set",
        ];

        if ($request->input('answers') == null) {
            $status = "Failed";
            $statusText = "Better luck next time";
            $assessmentProgressData['status'] = "Failed";
            $assessment_progress = $this->assessment_progress_model->create($assessmentProgressData);

            if (!$assessment_progress) {
                return response()->json([
                    'success' => false,
                    "message" => "Failed to save assessment record."
                ]);
            }

            return response()->json([
                'success' => true,
                "score" =>  0,
                "percentage" => 0,
                "status" => $status,
                "statusText" => $statusText,
                'progress_id' => $assessment_progress->id,
            ]);
        }

        $assessment_progress = $this->assessment_progress_model->create($assessmentProgressData);

        if (!$assessment_progress) {
            return response()->json([
                'success' => false,
                "message" => "Failed to save assessment record."
            ]);
        }

        foreach ($request->input('answers')[$request->assessment_id] as $answer) {
            $qid = $answer['qid'];
            $cid = $answer['cid'];

            $progress_details = $this->assessment_progress_details_model->create([
                'progress_id' => $assessment_progress->id,
                'qid' => $qid,
                'cid' => $cid,
            ]);

            if (!$progress_details) {
                return response()->json([
                    'success' => false,
                    "message" => "Failed to save assessment progress details."
                ]);
            }

            $answer_key = $this->answer_key_model->where('question_id', $qid)->first();

            if (!$answer_key) {
                return response()->json([
                    'success' => false,
                    "message" => "Question id do not exists"
                ]);
            }

            if ($answer_key->choice_id == $cid) {
                $score += 1;
            }
        }

        $percentage = round(($score / $totalQuestion) * 100, 2);

        if ($percentage > 75) {
            $status = "Passed";
            $statusText = "Nice job, you Passed!";
        } else {
            $status = "Failed";
            $statusText = "Better luck next time";
        }

        $scores = $this->assessment_progress_model->find($assessment_progress->id);

        $scores = $scores->update([
            'score' => $score,
            'status' => $status,
        ]);

        if (!$scores) {
            return response()->json([
                'success' => false,
                "message" => "Failed to update assessment scores"
            ]);
        }

        return response()->json([
            'success' => true,
            "score" =>  $score,
            "percentage" => $percentage,
            "status" => $status,
            "statusText" => $statusText,
            'progress_id' => $assessment_progress->id,
        ]);
    }

    public function progress(Request $request)
    {
        $fis_classes_ids = [];
        $assessments_ids = [];

        if (Gate::allows('fi_only')) {
            $fi = User::find(Auth::id());
            $classes = $fi->activeClasses;

            foreach ($classes as $class) {
                $fis_classes_ids[] = $class->id;
            }

            $assessments = $this->assessment_model->whereIn('class_id', $fis_classes_ids)->get();
            foreach ($assessments as $item) {
                $assessments_ids[] = $item->id;
            }

            $data = $this->assessment_progress_model->with('user')
                ->whereIn('assessment_id', $assessments_ids)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $data = Gate::allows('admin_lvl1') ? $this->assessment_progress_model->with(['user'])->orderBy('created_at', 'desc')->get() : $this->assessment_progress_model->with('user')->where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        }

        if ($request->ajax()) {

            return DataTables::of($data)
                ->addColumn('user_name', function ($row) {
                    return optional($row->user)->name ?? 'N/A';
                })
                ->addColumn('action', function ($row) {
                    //
                    $viewBtn = '<a href= " ' . route('assessment.view.progress', ['progress_id' => $row->id]) . ' " class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="view"><i class="fa-solid fa-eye"></i></a>';
                    return $viewBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('pages.classes.assessments.progress');
    }


    public function viewProgress($progress_id)
    {

        $view_progress = $this->assessment_progress_model->with(['ProgressDetails', 'assessment.question.choices.answer_key'])->find($progress_id);
        $progress_detail = $view_progress->ProgressDetails;
        $assessment = $view_progress->assessment;
        $questions = $view_progress->assessment->question;
        return view('pages.classes.assessments.view_progress', [
            'progress_detail' => $progress_detail,
            'assessment_progress' => $view_progress,
            'assessment' => $assessment,
            'questions' => $questions,
        ]);
    }
}
