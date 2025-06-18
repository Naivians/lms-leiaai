<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use App\Services\UserRestrictions;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\lessons;
use App\Models\Materials;
use App\Models\CourseModel;
use App\Models\Classes;

class LessonsController extends Controller
{
    private $lesson_model;
    private $materials_model;
    private $class_model;
    private $course_model;

    public function __construct(Lessons $lesson_model, Materials $materials_model, CourseModel $course_model, Classes $class_model)
    {
        $this->lesson_model = $lesson_model;
        $this->materials_model = $materials_model;
        $this->class_model = $class_model;
        $this->course_model = $course_model;
    }

    // lessons
    function index($class_id)
    {
        return view('pages.classes.lessons', [
            'class_id' => $class_id,
        ]);
    }

    function store(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'title' => 'required|string|max:255',
        //     'description' => 'nullable|string',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json(['errors' => $validator->errors()], 422);
        // }



        // $img = $request->file('img');
        // $img_name = time() . '_' . $img->getClientOriginalName();
        // $img->move(public_path('uploads/users'), $img_name);
        // $img_path = 'uploads/users/' . $img_name;

        $decrypted_class_id = Crypt::decrypt($request->class_id);
        $course_name = $this->class_model->select('course_name')->where('id', $decrypted_class_id);
        $course_id = CourseModel::get_course_id($course_name);

        $lessons = $this->lesson_model->create([
            'course_id' => $course_id,
            'title' => $request->title,
            'description' => $request->lessons_content ?? null,
        ]);

        if (!$lessons) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create lessson, please contact the registrar.',
            ], 404);
        }

        $attachmentPaths = [];
        $attachments = $request->file('attachments');

        if (is_array($attachments) && count($attachments) > 0) {
            $this->store_attachments($attachments, $lessons->id);
        }

        return response()->json([
            'success' => true,
            'message' => "Lesson created successfully",
        ], 200);
    }

    function store_attachments($attachments, $lesson_id)
    {
        $attachmentPaths = [];
        $folder = "img";
        $accepted_extensions = ['jpg', 'jpeg', 'mp4', 'mp3', 'pdf'];
        foreach ($attachments as $attachment) {
            $attachmentPaths[] = [
                'name' => time() . '-' . $attachment->getClientOriginalName(),
                'extention' => $attachment->getClientOriginalExtension(),
                'size' => $attachment->getSize(),
                'type' => $attachment->getClientMimeType(),
                'path' => $attachment->getPathname(),
            ];
        }

        foreach ($attachmentPaths as $file) {

            $folder = match ($file['extention']) {
                'jpg', 'jpeg' => 'img',
                'mp4' => 'video',
                'mp3' => 'audio',
                'pdf' => 'docs',
                default => 'others',
            };

            $path = $attachment->storeAs("uploads/lessons/$folder", $file['name'], 'public');

            $materials = $this->materials_model->create([
                'lessons_id' => $lesson_id,
                'type' => $file['type'],
                'size' => $file['size'],
                'path' => Storage::url($path),
            ]);

            if (!$materials) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to store attachments',
                ], 404);
            }
        }

        return;
    }
}
