<?php

namespace App\Services;
use App\Models\Classes;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Validator;

class ClassId
{
    public static function getClassId($classId)
    {
        try {
            $decryptedClassId = Crypt::decryptString($classId);
            $validator = Validator::make(['class_id' => $decryptedClassId], [
                'class_id' => 'required|exists:classes,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors(),
                ], 422);
            }

            return Classes::findOrFail($decryptedClassId);
        } catch (DecryptException $e) {
            return response()->json([
                'message' => 'Invalid class ID.',
            ], 400);
        }
    }
}
