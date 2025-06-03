<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Services\EmailService;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display the users DataTable view.
     */
    public function Index(Request $request)
    {

        if (Auth::user()->role != 3 && Auth::user()->role != 4 && Auth::user()->role != 5) {
            return redirect()->route('user.Dashboard')->withErrors([
                'access' => 'You do not have permission to access this page.'
            ]);
        }

        if ($request->ajax()) {
            $data = User::where('role', '!=', 5)
                ->orderBy('created_at', 'desc')
                ->get();

            return DataTables::of($data)
                ->addColumn('role_name', function ($row) {
                    $roles = [
                        0 => 'Students',
                        1 => 'FI',
                        2 => 'CGI',
                        3 => 'Registrar',
                        4 => 'Admin',
                    ];
                    return $roles[$row->role ?? 'Unknown'];
                })
                ->addColumn('student_name', function ($row) {
                    $student_name = ucfirst($row->lname) . ' ' . strtoupper($row->suffix) . ', ' . ucfirst($row->fname) . ', ' . ucfirst($row->mname);
                    return $student_name;
                })
                ->addColumn('action', function ($row) {
                    $viewBtn = '<a href= " ' . route('user.view', ['userId' => $row->id]) . ' " class="btn btn-sm btn-primary w-100 mb-2"><i class="fa-solid fa-eye"></i></a>';
                    $editBtn = '<a href= " ' . route('user.Edit', ['userId' => $row->id]) . ' " class="btn btn-sm btn-warning w-100"><i class="fa-solid fa-user-pen"></i></a>';
                    return $viewBtn . ' ' . $editBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('pages.users.user');
    }

    /**
     * Show the form to add new users.
     */
    public function Register()
    {
        if (Auth::user()->role === 3 || Auth::user()->role === 4 || Auth::user()->role === 5) {
            return view('pages.users.add_users');
        }

        return redirect()->route('user.Dashboard')->withErrors([
            'access' => 'You do not have permission to access this page.'
        ]);
    }

    public function Dashboard()
    {
        return view('Dashboard');
    }

    public function ViewUsers($userId)
    {
        $user = User::find($userId);

        $roles = [
            0 => 'Students',
            1 => 'FI',
            2 => 'CGI',
            3 => 'Registrar',
            4 => 'Admin',
            5 => 'Super Admin',
        ];
        $gender = [
            0 => 'Male',
            1 => 'Female',
            2 => 'Rather not say',
        ];

        return view('pages.users.view_users', ['users' => $user, 'roles' => $roles[$user->role], 'gender' => $gender[$user->gender]]);
    }

    public function Store(Request $request, EmailService $emailService)
    {

        $validator = Validator::make($request->all(), [
            'id_number' => 'nullable|string|unique:users,id_number',
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'mname' => 'string|max:255',
            'suffix' => 'nullable|string|max:50',
            'contact' => ['required', 'string', 'regex:/^\+\d{10,15}$/'],
            'email' => 'required|string|unique:users,email',
            'password' => 'required|confirmed|min:8',
            'role' => 'required|in:0,1,2,3,4',
            'gender' => 'required|in:0,1,2',
            'img' => 'image|mimes:jpeg,jpg,png|max:1024',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(), // First error message
                'errors' => $validator->errors(),           // All error messages
            ], 422);
        }

        $gender_img = [
            0 => asset('assets/img/student-male.png'),
            1 => asset('assets/img/student-female.jpg'),
            2 => asset('assets/img/logo.jpg'),
        ];

        if ($request->hasFile('img')) {
            $img = $request->file('img');
            $img_name = time() . '_' . $img->getClientOriginalName();
            $img->move(public_path('uploads/users'), $img_name);
            $img_path = 'uploads/users/' . $img_name;
        } else {
            if ($request->role == 1 || $request->role == 2) {
                $img_path = asset('assets/img/pilot.png');
            }
            $img_path = $gender_img[$request->gender];
        }

        $user = User::create([
            'id_number' => $request->id_number ?? null,
            'fname' => $request->fname,
            'lname' => $request->lname,
            'mname' => $request->mname ?? null,
            'suffix' => $request->suffix ?? null,
            'contact' => $request->contact,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'gender' => $request->gender,
            'img' => $img_path ?? null,
            'verification_token' => Str::uuid(),
        ]);



        // $verificationLink = route('auth.email.verify', ['token' => $user->verification_token]);
        $emailService->SendVerificationLink($user, route('auth.email.verify', ['token' => $user->verification_token]));

        // return response()->json([
        //     'errors' => ,           // All error messages
        // ], 422);

        return response()->json([
            'success' => true,
            'message' => 'Registration successful. Please verify your email.',
        ]);
    }


    public function EditUser($userId)
    {
        $user = User::find($userId);

        $roles = [
            0 => 'Students',
            1 => 'FI',
            2 => 'CGI',
            3 => 'Registrar',
            4 => 'Admin',
            5 => 'Super Admin',
        ];
        $gender = [
            0 => 'Male',
            1 => 'Female',
            2 => 'Rather not say',
        ];
        return view('pages.users.add_users', ['users' => $user, 'roles' => $roles[$user->role], 'gender' => $gender[$user->gender]]);
    }


    public function UpdateUser(Request $request)
    {
        $user = User::find($request->id);
        $new_id_number = $request->id_number;
        $validator = Validator::make($request->all(), [
            'id_number' => 'nullable|string',
            'fname' => 'string|max:255',
            'lname' => 'string|max:255',
            'mname' => 'nullable|string|max:255',
            'suffix' => 'nullable|string|max:50',
            'contact' => ['string', 'regex:/^\+\d{10,15}$/'],
            'email' => 'string|nullable',
            'role' => 'in:0,1,2,3,4',
            'gender' => 'in:0,1,2',
            'img' => 'image|mimes:jpeg,jpg,png|max:1024',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        if (!empty($request->id_number) && $request->id_number != $user->id_number) {
            $exists = User::where('id_number', $request->id_number)->exists();
            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID Number already Exists',
                ], 404);
            }
        } else {
            $new_id_number = $user->id_number;
        }

        if ($request->hasFile('img')) {
            if (!empty($user->img) && File::exists(public_path($user->img))) {
                File::delete(public_path($user->img));
            }
            $img = $request->file('img');
            $img_name = time() . '_' . $img->getClientOriginalName();
            $img->move(public_path('uploads/users'), $img_name);
            $img_path = 'uploads/users/' . $img_name;
        } else {

            $gender_img = [
                0 => asset('assets/img/student-male.png'),
                1 => asset('assets/img/student-female.jpg'),
                2 => asset('assets/img/logo.jpg'),
            ];

            $img_path = $gender_img[$request->gender];
        }

        $user->update([
            'id_number' => $new_id_number,
            'fname' => $request->fname,
            'lname' => $request->lname,
            'mname' => $request->mname ?? null,
            'suffix' => $request->suffix ?? null,
            'contact' => $request->contact ?? $user->contact,
            'role' => $request->role ?? $user->role,
            'gender' => $request->gender ?? $user->gender,
            'img' => $img_path,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User update successfully.',
        ]);
    }
}
