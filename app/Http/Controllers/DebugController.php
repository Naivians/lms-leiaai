<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class DebugController extends Controller
{
    public function index()
    {
        $users = User::limit(3)->get();
        return view('debug', ['users' => $users]);
    }
}
