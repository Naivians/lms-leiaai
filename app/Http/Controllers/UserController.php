<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables; // ✅ Correct import

class UserController extends Controller
{
    /**
     * Display the users DataTable view.
     */
    public function Index(Request $request)
    {
    if ($request->ajax()) {
            $data = User::where('role', '!=', 4)->get();

            return DataTables::of($data)
                ->addColumn('role_name', function($row){
                    $roles = [
                        0 => 'Students',
                        1 => 'FI',
                        2 => 'CGI',
                        3 => 'Admin',
                        4 => 'Super Admin',
                    ];
                    return $roles[$row->role ?? 'Unknown'];

                })
                ->addColumn('action', function ($row) {
                    $viewBtn = '<a href= " ' . route('user.view', ['userId' => $row->id]) .' " class="btn btn-sm btn-primary w-100"><i class="fa-solid fa-eye"></i></a>';
                    return $viewBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('pages.users.user');
    }

    /**
     * Show the form to add new users.
     */
    public function addUsers()
    {
        return view('pages.users.add_users');
    }
    public function ViewUsers($userId)
    {
        return view('pages.users.view_users');
    }
}
