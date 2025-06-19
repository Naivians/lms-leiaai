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
use App\Models\Material;
use App\Models\CourseModel;
use App\Models\Classes;

class LessonsController extends Controller
{
    private $lesson_model;
    private $materials_model;
    private $class_model;
    private $course_model;

    public function __construct(Lessons $lesson_model, Material $materials_model, CourseModel $course_model, Classes $class_model)
    {
        $this->lesson_model = $lesson_model;
        $this->materials_model = $materials_model;
        $this->class_model = $class_model;
        $this->course_model = $course_model;
    }

    // lessons
    function index($class_id, $lesson_id)
    {
        $pdfs = [];
        $imgs = [];
        $videos = [];
        if ($lesson_id) {
            $lessons = $this->lesson_model->find($lesson_id);
            $materials = $lessons->materials;
        }

        // foreach($materials as $material){
        //     if($material->extension == 'pdf'){
        //         $pdfs[] = [
        //             'filename' => $material->filename,
        //             'extension' => $material->extension,
        //             'path' => $material->path,
        //         ];
        //     }
        // }

        // dd($pdfs);

        return view('pages.classes.lessons', [
            'class_id' => $class_id,
            'lessons' => $lessons ?? null,
            'materials' => $materials ?? null
        ]);
    }

    function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

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
        $accepted_extensions = [
            'jpg' => 'IMG',
            'jpeg' => 'IMG',
            'png' => 'IMG',
            'mp4' => 'VID',
            'mp3' => 'AUD',
            'pdf' => 'DOCS',
        ];

        foreach ($attachments as $attachment) {
            $extension = strtolower($attachment->getClientOriginalExtension());

            $filename = $accepted_extensions[$extension] . '-' . time() . '.' . $extension;

            $attachmentPaths[] = [
                'filename'     => $filename,
                'extension' => $extension,
                'size'     => $attachment->getSize(),
                'type'     => $attachment->getClientMimeType(),
                'path'     => $attachment->getPathname(),
            ];
        }

        foreach ($attachmentPaths as $file) {

            $folder = match ($file['extension']) {
                'jpg', 'jpeg', 'png' => 'img',
                'mp4' => 'video',
                'mp3' => 'audio',
                'pdf' => 'docs',
                default => 'others',
            };

            $path = $attachment->storeAs("uploads/lessons/$folder", $file['filename'], 'public');

            $materials = $this->materials_model->create([
                'lessons_id' => $lesson_id,
                'filename' => $filename,
                'type' => $file['type'],
                'extension' => $file['extension'],
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

    function Destroy($material_id)
    {
        $material = $this->materials_model->find($material_id);
        $res = $material->delete();

        if (!$res) {
            return response()->json([
                'success' => false,
                'message' => "failed to delete attachment"
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => "Attachment successfully deleted!"
        ]);
    }

    function deleteLesson($lesson_id){
        $lesson = $this->lesson_model->find($lesson_id);
        $res = $lesson->delete();

        if (!$res) {
            return response()->json([
                'success' => false,
                'message' => "failed to delete lesson"
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => "Lesson successfully deleted!"
        ]);
    }
}
