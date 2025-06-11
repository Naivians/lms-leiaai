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
use Illuminate\Support\Facades\DB;

class ClassController extends Controller
{
    private $classModel;
    private $courseModel;
    private $userModel;
    private $enrollment;
    public $class_id;
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
        $classes = (Auth::user()->role == 3 || Auth::user()->role == 4 || Auth::user()->role == 5) ? $this->classModel->where('active', 1)->get() : Auth::user()->activeClasses;
        $courses = $this->courseModel->all();
        return view('pages.classes.index', [
            'classes' => $classes,
            'courses' => $courses,
        ]);
    }

    function Archives(){
        $user = $this->userModel->find(Auth::id());
        $user =  $user->inactiveClasses;
        session()->put('archives', count($user));
        return view('pages.classes.archives.archives', [
            'archives' => $user,
        ]);
    }

    function Stream($class_id)
    {
        return view('pages.classes.stream', [
            'class_id' => $class_id,
        ]);
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

        $getCgi = $this->userModel->select('id', 'role')->where('role', 2)->get();


        if (count($getCgi) > 0) {
            foreach ($getCgi as $cgi) {
                $this->enrollment->create([
                    'user_id' => $cgi->id,
                    'class_id' => $class->id,
                    'role_id' => $cgi->role
                ]);
            }
        }

        if (!$class) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create class',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => "Class created successfully",
        ], 200);
    }

    function Show($classId)
    {
        $decryptedClassId = Crypt::decrypt($classId);
        $class = $this->classModel->findOrFail($decryptedClassId);

        if (!$class) {
            return response()->json([
                'success' => false,
                'message' => 'Class not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $class,
        ], 200);
    }

    function ArchiveClass($classId)
    {
        $decryptedClassId = Crypt::decrypt($classId);
        $class = $this->classModel->findOrFail($decryptedClassId);

        if (!$class) {
            return response()->json([
                'success' => false,
                'message' => 'Class not found',
            ], 404);
        }
        $status = true;

        if($class->active){
            $status = false;
        }

        $class->active = $status;
        $class->save();
        return response()->json([
            'success' => true,
            'message' => 'Class successfully moved to archive',
        ], 200);
    }

    function Update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'edit_class_name' => 'required|string|max:255',
            'edit_class_description' => 'required|string|max:255',
            'edit_course_name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $class = $this->classModel->findOrFail($request->edit_class_id);

        if (!$class) {
            return response()->json([
                'success' => false,
                'message' => 'Class not found',
            ], 404);
        }

        $class->update([
            'class_name' => $request->edit_class_name,
            'class_description' => $request->edit_class_description,
            'course_name' => $request->edit_course_name,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Class updated successfully',
        ], 200);
    }

    function getEnrolledUsers($class_id)
    {

        $decryptedClassId = Crypt::decrypt($class_id);

        $isExists = $this->classModel->where('id', $decryptedClassId)->exists();

        if (!$isExists) {
            return response()->json([
                'success' => false,
                'message' => 'Class not found',
            ], 404);
        }

        $fi = $this->enrollment->get_fi_and_cgi($decryptedClassId);
        $students = $this->enrollment->get_enrolled_students($decryptedClassId);

        if (!$fi && !$students) {
            return response()->json([
                'success' => false,
                'message' => 'No enrolled users found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data1' => $fi,
            'data2' => $students,
        ], 200);
    }
}
