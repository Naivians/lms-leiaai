<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classes;
use App\Models\CourseModel;
use App\Models\ClassUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class ClassController extends Controller
{
    private $classModel;
    private $courseModel;
    private $userModel;
    private $enrollment;
    public function __construct(Classes $classModel, CourseModel $courseModel, ClassUser $enrollment, User $userModel)
    {
        $this->classModel = $classModel;
        $this->courseModel = $courseModel;
        $this->enrollment = $enrollment;
        $this->userModel = $userModel;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function Index()
    {
        $classes = (Auth::user()->role == 3 || Auth::user()->role == 4 || Auth::user()->role == 5) ? $this->classModel->all() : Auth::user()->classes;
        $courses = $this->courseModel->all();
        // $classes = Auth::user()->classes;

        return view('pages.classes.index', [
            'classes' => $classes,
            'courses' => $courses,
        ]);
    }
    function Stream($class_id)
    {
        return view('pages.classes.stream');
    }
    function Instructor()
    {
        return view('pages.classes.instructor');
    }
    function Classwork()
    {
        return view('pages.classes.classwork');
    }
    function Grade()
    {
        return view('pages.classes.grade');
    }
    function Announcements()
    {
        return view('pages.classes.announcement');
    }

    function Store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'class_name' => 'required|string|max:255',
            'class_description' => 'required|string|max:255',
            'course_name' => 'required|string',
            'class_image' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $class = $this->classModel->create([
            'class_name' => $request->class_name,
            'class_description' => $request->class_description,
            'course_name' => $request->course_name,
            'user_id' => 3,
            'class_code' => strtoupper(uniqid($request->course_name . '_')),
        ]);

        $getCgi = $this->userModel->select('id')->where('role', 2)->get()->toArray();

        if (count($getCgi) > 0) {
            foreach ($getCgi as $cgi) {
                $enrollment = $this->enrollment->create([
                    'user_id' => $cgi,
                    'class_id' => $class->id
                ]);
            }
        }

        $enrollment = $this->enrollment->create([
            'user_id' => $cgi,
            'class_id' => $class->id
        ]);


        if (!$class && !$enrollment) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create class',
            ], 500);
        }

        return response()->json([
            'success' => false,
            'message' => "Class created successfully",
        ], 200);
    }

    function Edit($classId)
    {
        $class = $this->classModel->findOrFail($classId);
        $courses = $this->courseModel->all();

        // return view('pages.classes.edit', [
        //     'class' => $class,
        //     'courses' => $courses,
        // ]);

        return view('pages.classes.announcement');
    }
}
