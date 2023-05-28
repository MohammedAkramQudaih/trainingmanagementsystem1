<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StoredFile;
use Illuminate\Http\Request;
use App\Models\User;

class StoredFileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //no need to it
        return response()->json([
            'message' => 'listing all stored Files'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'fileName' => 'required|string',
            'fileUrl' => 'required|string|unique:stored_files,fileUrl',
            'fileType' => 'required|string',
            'fileSize' => 'required|integer',
            'trainee_id' => 'sometimes|integer',
            'program_id' => 'sometimes|integer',
            'notes' => 'nullable|string'
        ]);
        $storedFile = new StoredFile();
        $storedFile->fileName = $request->input('fileName');
        $storedFile->fileUrl = $request->input('fileUrl');
        $storedFile->fileType = $request->input('fileType');
        $storedFile->fileSize = $request->input('fileSize');
        $storedFile->trainee_id = $request->input('trainee_id') ?? null;
        $storedFile->program_id = $request->input('program_id') ?? null;
        $storedFile->notes = $request->input('notes');

        $storedFile->save();
        return response()->json([
            'File' => $storedFile,
            'message' => 'The File Uploaded Successfully'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $file = StoredFile::withoutTrashed()->find($id);
        return response()->json($file, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $file = StoredFile::withoutTrashed()->find($id);
        $file->delete();
        return response()->json([
            'message' => 'The File deleted Successfully'
        ]);
    }
    public function getTraineeFiles($trainee_id) {
        $files = StoredFile::withoutTrashed()->where('trainee_id',$trainee_id)->get();
        return response()->json([
            'files' => $files
        ]);
    }
    public function getProgramLogo($program_id) {
        $files = StoredFile::withoutTrashed()->where('program_id',$program_id)->get();
        return response()->json([
            'files' => $files
        ]);
    }
}
