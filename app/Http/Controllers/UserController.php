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
            $data = User::query();

            return DataTables::of($data)
                ->addColumn('action', function ($row) {
                    $viewBtn = '<a href= " ' . route('user.view', ['userId' => $row->id]) .' " class="btn btn-sm btn-warning"><i class="fa-solid fa-eye"></i></a>';
                    $editBtn = '<button  class="btn btn-sm btn-primary" id=" ' . $row->id . ' "><i class="fa-solid fa-pen-to-square"></i></button>';
                    $deleteBtn = '<button  class="btn btn-sm btn-danger" id=" ' . $row->id . ' "><i class="fa-solid fa-trash"></i></button>';
                    return $viewBtn . ' ' . $editBtn . ' ' . $deleteBtn;
                })
                ->rawColumns(['action']) // Important to render HTML
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
