<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classes;
use App\Models\CourseModel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class ClassController extends Controller
{
    private $classModel;
    private $courseModel;
    private $userModel;
    public function __construct(Classes $classModel, CourseModel $courseModel)
    {
        $this->classModel = $classModel;
        $this->courseModel = $courseModel;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function Index()
    {
        $classes = (Auth::user()->role == 3 || Auth::user()->role == 4 || Auth::user()->role == 5) ? $this->classModel->all() : $this->classModel->where('cgi_id', Auth::user()->role)->get();
        $courses = $this->courseModel->all();



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
            'course_id' => 'required',
            'class_image' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        if ($request->hasFile('class_image')) {
            $image = $request->file('class_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $file_path = 'uploads/classes/' . $imageName;
            $image->move(public_path('uploads/classes'), $imageName);
        } else {
            $file_path = asset('assets/img/leiaai_logo.png');
        }


        $class = $this->classModel->create([
            'class_name' => $request->class_name,
            'class_description' => $request->class_description,
            'course_id' => $request->course_id,
            'file_path' => $file_path,
            'cgi_id' => 2,
            'class_code' => strtoupper(uniqid('CLASS_')),
        ]);

        if (!$class) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create class',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Class created successfully',
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
