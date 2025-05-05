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
    public function index(Request $request)
    {
        // If AJAX request, return JSON data for DataTables
        if ($request->ajax()) {
            return DataTables::of(User::query())->make(true);
        }

        // Otherwise, return the Blade view
        return view('pages.users.user'); // ✅ This should be your DataTable Blade view
    }

    /**
     * Show the form to add new users.
     */
    public function addUsers()
    {
        return view('pages.users.add_users');
    }
}
