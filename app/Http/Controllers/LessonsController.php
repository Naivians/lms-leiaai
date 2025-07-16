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
        if ($lesson_id) {
            $lessons = $this->lesson_model->with('materials')->find($lesson_id);
        }


        return view('pages.classes.lessons', [
            'class_id' => $class_id,
            'lessons' => $lessons ?? null,
        ]);
    }

    function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'lessons_content' => 'required|string',
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

    function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'lessons_content' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $lesson = $this->lesson_model->find($request->lesson_id);

        $lessons = $lesson->update([
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
            $this->store_attachments($attachments, $request->lesson_id);
        }

        return response()->json([
            'success' => true,
            'message' => "Lesson updated successfully",
        ], 200);
    }

    function store_attachments($attachments, $lesson_id)
    {
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
            $filename = $attachment->getClientOriginalName();

            $folder = match ($extension) {
                'jpg', 'jpeg', 'png' => 'img',
                'mp4' => 'video',
                'mp3' => 'audio',
                'pdf' => 'docs',
                default => 'others',
            };

            $uploadPath = public_path("uploads/lessons/$folder");
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true); // Create folder recursively
            }

            $filePath = $uploadPath . '/' . $filename;
            if (!move_uploaded_file($attachment->getPathname(), $filePath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to move uploaded file',
                ], 404);
            }

            $materials = $this->materials_model->create([
                'lessons_id' => $lesson_id,
                'filename' => $filename,
                'type' => $attachment->getClientMimeType(),
                'extension' => $extension,
                'size' => $attachment->getSize(),
                'path' => "/uploads/lessons/$folder/" . $filename, // ✅ No /storage
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

    function deleteLesson($lesson_id)
    {
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

    public function viewPDF($pdf_url)
    {
        $decodedPath = base64_decode($pdf_url);
        return view('pages.classes.view_pdf', ['pdf_url' => $decodedPath]);
    }
}
