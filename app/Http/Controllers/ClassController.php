<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClassController extends Controller
{
    function Index()
    {
        return view('pages.classes.index');
    }
    function Stream()
    {
        return view('pages.classes.stream');
    }
    function People()
    {
        return view('pages.classes.people');
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
}
