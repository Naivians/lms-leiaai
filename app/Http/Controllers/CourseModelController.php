<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CourseModel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class CourseModelController extends Controller
{
    private $course;
    public function __construct()
    {
        $this->course = new CourseModel();
    }

    public function Index(Request $request)
    {
        if ($request->ajax()) {
            $data = CourseModel::orderBy('created_at', 'desc')->get();

            return DataTables::of($data)
                ->addColumn('action', function ($row) {
                    $editBtn = '<i class="fa-solid fa-user-pen btn btn-warning" onclick ="showCourse(' . $row->id . ')"></i>';
                    $deleteBtn = '<i class="fa-solid fa-trash btn  btn-danger" onclick ="deleteCourse(' . $row->id . ')"></i>';

                    if (Auth::user()->role === 5) {
                        return $editBtn . ' ' . $deleteBtn;
                    }
                })
                ->make(true);
        }

        return view('pages.courses.courses');
    }

    function Show($courseId)
    {
        $course = $this->course->find($courseId);

        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Course retrieved successfully.',
            'data' => $course,
        ]);
    }

    function update(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'course_name' => 'required|string',
            'course_description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $exists = $this->course->where('course_name', $request->course_name)->exists();

        if ($exists) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course name already exists.',
            ], 422);
        }

        $course = $this->course->find($request->course_id);

        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found.',
            ], 500);
        }

        $course = $course->update([
            'course_name' => $request->course_name,
            'course_description' => $request->course_description ?? null,
        ]);


        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update course. Please try again.',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Course updated successfully.',
        ]);
    }

    public function Create(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'course_name' => 'required|string|unique:course_models,course_name',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $courseDescription = [
            'PPL' => 'Private Pilot License',
            'CPL' => 'Commercial Pilot License',
            'ATPL' => 'Airline Transport Pilot License',
            'IR' => 'Instrument Rating',
            'ME' => 'Multi-Engine Rating',
            'FIC' => 'Flight Instructor Certificate',
        ];

        $course = $this->course->create([
            'course_name' => $request->course_name ?? null,
            'course_description' => $courseDescription[$request->course_name] ?? null,
        ]);

        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create course. Please try again.',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Course created successfully.',
        ]);
    }

    public function Destroy($course_id)
    {
        $course = $this->course->find($course_id);
        $course->delete();

        if (!$course) {
            return response()->json([
                'success' => 'error',
                'message' => 'Course not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Course deleted successfully.',
        ]);
    }
}
