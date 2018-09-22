<?php

namespace App\Http\Controllers\API;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class UploaderController extends Controller
{
    protected $storageDir = '/public/files';

    function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if (!$request->hasFile('file') || !$request->file('file')->isValid()) {
            return response()->json([
                'status' => 'error',
                'message' => 'The file was not uploaded!'
            ]);
        }

        $userDir = $this->getUserDir();

        $file = $request->file('file');

        $file->store($userDir);

        return response()->json([
            'status' => 'success',
            'message' => 'The file was uploaded!',
            'data' => [
                'title' => $file->getClientOriginalName(),
                'path' => $file->hashName()
            ]
        ]);
    }

    protected function getUserDir()
    {
        if (!Storage::exists($this->storageDir)) {
            Storage::makeDirectory($this->storageDir);
        }

        $userDir = $this->storageDir . '/' . auth()->id();

        if (!Storage::exists($userDir)) {
            Storage::makeDirectory($userDir);
        }

        return $userDir;
    }
}
