<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller
{
    /**
     * Display the users DataTable view.
     */
    public function Index(Request $request)
    {
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
                ->addColumn('student_name', function($row) {
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
        return view('pages.users.add_users');
    }

    public function ViewUsers($userId)
    {
        $user = User::find($userId);
        return view('pages.users.view_users', ['users' => $user]);
    }

    public function Store(Request $request)
    {
        // return response()->json(['data' => $request->all()]);
        $validated = $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'mname' => 'required|string|max:255',
            'suffix' => 'required|string|max:50',
            'contact' => ['required', 'string', 'regex:/^\+\d{10,15}$/'],
            'email' => 'required|string|unique:users,email',
            'password' => 'required|confirmed|min:8',
            'role' => 'required|in:0,1,2,3,4',
            'gender' => 'required|in:0,1,2',
            'img' => 'image|mimes:jpeg,jpg,png|max:1024',
        ]);

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
            if($request->role == 1 || $request->role == 2){
                $img_path = asset('assets/img/pilot.png');
            }
            $img_path = $gender_img[$request->gender];
        }

        $user = User::create([
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
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully.',
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
}
