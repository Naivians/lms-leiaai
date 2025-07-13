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

use App\Models\Classes;
use App\Models\CourseModel;
use App\Models\ClassUser;
use App\Models\User;
use App\Models\Announcement;
use App\Models\lessons;
use App\Models\Assessment;

class ClassController extends Controller
{
    private $classModel;
    private $courseModel;
    private $userModel;
    private $enrollment;
    private $class_id;
    private $userRestrictions;
    private $announcement;
    private $lesson_model;
    private $assessment_model;

    public function __construct(Classes $classModel, CourseModel $courseModel, ClassUser $enrollment, User $userModel, UserRestrictions $userRestrictions, Announcement $announcement, lessons $lesson_model, Assessment $assessment_model)
    {
        $this->classModel = $classModel;
        $this->courseModel = $courseModel;
        $this->enrollment = $enrollment;
        $this->userModel = $userModel;
        $this->userRestrictions = $userRestrictions;
        $this->announcement = $announcement;
        $this->lesson_model = $lesson_model;
        $this->assessment_model = $assessment_model;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function Index()
    {
        $classes = $this->userRestrictions->canPerformAction('admin_lvl1') ? $this->classModel->where('active', 1)->get() : Auth::user()->activeClasses;
        $courses = $this->courseModel->all();
        return response()->view('pages.classes.index', [
            'classes' => $classes,
            'courses' => $courses,
        ]);
    }

    function Archives()
    {
        $user = $this->userModel->find(Auth::id());

        $user = Gate::allows('admin_lvl1') ? $this->classModel->where('active', 0)->get() : $user->inactiveClasses;
        session()->put('archives', count($user));
        return view('pages.classes.archives.archives', [
            'archives' => $user,
        ]);
    }

    function Stream($class_id)
    {
        $encryptedClassId = $class_id;

        try {
            $class_id = Crypt::decrypt($class_id);
        } catch (DecryptException $e) {
            return redirect()->route('class.index')->withErrors([
                'error' => 'Invalid class ID',
            ]);
        }

        $announcements = Classes::with(['announcements.user'])->find(id: $class_id);
        $announcements = $announcements->announcements ?? null;

        $course = $this->classModel->select('id','course_name', 'class_name')->where('id', $class_id)->first();
        $course_id = CourseModel::get_course_id($course->course_name);

        $courses_lessons = $this->courseModel->find($course_id);
        $courses_lessons = $courses_lessons->lessons;

        $lesson_ids = [];
        $assessments_ids = [];

        foreach ($courses_lessons as $lesson) {
            $lesson_ids[] = $lesson->id;
        }

        $lessons = $this->lesson_model->with('materials')->whereIn('id', $lesson_ids)->get();
        $assessments = $this->assessment_model->with('progress.user')->where('class_id', $class_id)->get();

        return view('pages.classes.stream', [
            'class_id' => $encryptedClassId,
            'announcements' => $announcements ?? null,
            'lessons' => $courses_lessons,
            'assessments' => $assessments,
            'class_name' => $course->class_name,
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

    function Store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'class_name' => 'required|string|max:255',
            'start_date' => 'required|string|max:255',
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
            'user_id' => null,
            'class_code' => strtoupper(uniqid($request->course_name . '_')),
            'created_at' => $request->start_date ?? now(),
        ]);

        $getCgi = $this->userModel->select('id', 'role', 'name', 'email', 'isVerified', 'login_status')->where('role', 2)->get();


        if (count($getCgi) > 0) {
            foreach ($getCgi as $cgi) {
                $this->enrollment->create([
                    'user_id' => $cgi->id,
                    'class_id' => $class->id,
                    'role_id' => $cgi->role
                ]);

                try {
                    Mail::to($cgi->email)->queue(new enrollment_notification($cgi, $class));
                } catch (\Exception $e) {
                    Log::error('Failed to send enrollment email to ' . $cgi->email . ': ' . $e->getMessage());
                }
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

        if ($class->active) {
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

        try {
            $decryptedClassId = Crypt::decrypt($class_id);
        } catch (DecryptException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid class ID',
            ], 404);
        }

        $isExists = $this->classModel->where('id', $decryptedClassId)->exists();

        if (!$isExists) {
            return response()->json([
                'success' => false,
                'message' => 'Class not found',
            ], 404);
        }

        $enrolled_fi = $this->enrollment->get_fi_and_cgi($decryptedClassId);
        $enrolled_students = $this->enrollment->get_enrolled_students($decryptedClassId);

        $fi_html = null;
        $sp_html = null;
        $roles = "fi";


        if (!$enrolled_fi && !$enrolled_students) {
            return response()->json([
                'success' => false,
                'message' => 'No enrolled users found',
            ], 404);
        }

        foreach ($enrolled_fi as $fi) {
            $img = asset($fi->img);
            $restriction = Gate::allows('admin_lvl1') ? '<button class="btn btn-danger" onclick="removeUserFromCLass(' . $fi->id . ', \'students\')"><i class="fa-solid fa-trash"></i></button>' : '';

            $fi_html .= '
            <div class="card mb-2">
                <div class="card-header announcement_header">
                    <div class="announcement_header">
                        <div class="announcement_img_container">
                            <img src="' . $img . '" alt="">
                        </div>
                        <div>
                            <h5 class="mx-2 my-0">' . htmlspecialchars($fi->name) . '</h5>
                            <small class="mx-2">' . $fi->role_label . '</small>
                        </div>
                    </div>
                    <div class="edit_btn">
                    ' . $restriction . '
                    </div>
                </div>
            </div>';
        }

        foreach ($enrolled_students as $sp) {
            $img = asset($sp->img);
            $restriction = Gate::allows('admin_lvl1') ? '<button class="btn btn-danger" onclick="removeUserFromCLass(' . $sp->id . ', \'students\')"><i class="fa-solid fa-trash"></i></button>' : '';

            $sp_html .= '
            <div class="card mb-2">
                <div class="card-header announcement_header">
                    <div class="announcement_header">
                        <div class="announcement_img_container">
                            <img src="' . $img . '" alt="">
                        </div>
                        <div>
                            <h5 class="mx-2 my-0">' . htmlspecialchars($sp->name) . '</h5>
                        </div>
                    </div>
                    <div class="edit_btn">
                        ' . $restriction . '
                    </div>
                </div>
            </div>';
        }


        return response()->json([
            'success' => true,
            'data1' => $fi_html,
            'data2' => $sp_html,
        ], 200);
    }


    function Search(Request $request)
    {
        $search = $request->input('search');
        $roles = $request->input('roles') == 'fi' ? [1, 2] : [0];
        $users = $this->userModel->select('name', 'img', 'role', 'id')
            ->whereIn('role', $roles)
            ->where('isVerified', '=', 1)
            ->where('name', 'Like', '%' . $search . '%')->get();
        $html = '';

        foreach ($users as $user) {
            $img = asset($user->img);

            $html .= '
            <div class="card mb-2">
                <div class="card-header announcement_header">
                    <div class="announcement_header">
                        <div class="announcement_img_container">
                            <img src="' . $img . '" alt="">
                        </div>
                        <div>
                            <h5 class="mx-2 my-0">' . htmlspecialchars($user->name) . '</h5>
                        </div>
                    </div>
                    <div class="edit_btn">
                        <button class="btn btn-danger" onclick="enrollUser(' . $user->id . ', ' . $user->role . ')"><i class="fa-solid fa-square-plus"></i></button>
                    </div>
                </div>
            </div>';
        }

        return response()->json([
            'success' => true,
            'html' => $html
        ]);
    }

    function Enroll(Request $request)
    {
        $userId = $request->input('userId');
        $role_id = $request->input('roleId');
        $classId = Crypt::decrypt($request->input('classId'));

        $isExists = $this->enrollment->where([
            ['user_id', '=', $userId],
            ['class_id', '=', $classId],
        ])->first();

        if ($isExists) {
            return response()->json([
                'success' => false,
                'message' => "This user already enrolled in this class",
            ]);
        }

        $user = $this->userModel->find($userId);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => "this user doesn't exists on our database",
            ]);
        }


        $user = $this->enrollment->create([
            'user_id' => $userId,
            'class_id' => $classId,
            'role_id' => $user->role,
        ]);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => "Failed to enroll user in this class",
            ]);
        }

        $classes = $user->class;
        $userInfo = $user->user;

        try {
            Mail::to($userInfo->email)->queue(new enrollment_notification($userInfo, $classes));
        } catch (\Exception $e) {
            Log::error('Failed to send enrollment email to ' . $userInfo->email . ': ' . $e->getMessage());
        }



        return response()->json([
            'success' => true,
            'message' => "User successfully enrolled in this class",
        ]);
    }

    function RemoveUserFromClass(Request $request)
    {
        $userId = $request->input('userId');

        $isExists = $this->enrollment->where([
            ['user_id', '=', $userId],
        ])->first();

        if (!$isExists) {
            return response()->json([
                'success' => false,
                'message' => "This user is not enrolled in this class",
            ]);
        }

        $isExists->delete();

        return response()->json([
            'success' => true,
            'message' => "User successfully removed from this class",
        ]);
    }
}
