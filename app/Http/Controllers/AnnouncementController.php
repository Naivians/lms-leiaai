<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use App\Services\UserRestrictions;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    private $announcement;
    public function __construct(Announcement $announcement)
    {
        $this->announcement = $announcement;
    }
    public function index($class_id, $announcement_id)
    {
        if ($announcement_id != 0) {
            $announcement = $this->announcement->find($announcement_id);

            if(!$announcement) {
                return redirect()->route('class.stream', ['class_id' => $class_id])->with('error', 'Announcement id not found.');
            }

            return view('pages.classes.announcement', ['class_id' => $class_id ?? null, 'announcement' => $announcement]);
        }

        return view('pages.classes.announcement', ['class_id' => $class_id ?? null]);
    }

    public function store(Request $request)
    {

        try {
            $class_id = Crypt::decrypt($request->class_id);
        } catch (DecryptException $e) {
            return response()->json([
                'success' => false,
                'message' => "Invalid class ID.",
            ], 404);
        }

        if ($request->announcement_content == '') {
            return response()->json([
                'success' => false,
                'message' => "Announcement content cannot be empty.",
            ], 400);
        }

        $announcement = $this->announcement->create([
            'class_id' => $class_id,
            'user_id' => Auth::id(),
            'content' => $request->announcement_content,
        ]);

        if (!$announcement) {
            return response()->json([
                'success' => false,
                'message' => "Failed to create announcement.",
            ], 500);
        }



        return response()->json([
            'success' => true,
            'message' => 'Announcement created successfully.',
        ], 200);
    }



    /**
     * Display the specified resource.
     */
    public function show(Announcement $announcement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Announcement $announcement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'announcement_content' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $announcement = $this->announcement->find($request->announcement_id);

        if (!$announcement) {
            return response()->json([
                'success' => false,
                'message' => "Announcement not found.",
            ], 404);
        }

        $announcement->content = $request->announcement_content;
        $announcement->save();

        return response()->json([
            'success' => true,
            'message' => 'Announcement updated successfully.',
            'redirect' => route('class.stream', ['class_id' => $request->class_id]),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Announcement $announcementId)
    {
        $announcementId->delete();
        return response()->json([
            'success' => true,
            'message' => 'Announcement deleted.'
        ]);
    }
}
