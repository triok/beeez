<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class UploadController extends Controller
{
    public function store(Request $request)
    {
        $key = $this->getKey($request);

        if ($request->ajax()
            && $request->hasFile('file')
            && $request->file('file')->isValid()) {

            $file = $request->file('file');
            $name = str_random(30) . $file->getClientOriginalName();
            request()->file->storeAs('/public/jobs/upload', $name);

            Session::push($key, [
                'file' => $name,
                'size' => $file->getSize(),
                'type' => $file->getMimeType(),
                'original_name' => $file->getClientOriginalName(),
            ]);

            unset($file, $name);
        }

        return response()->json([
            'key' => $key,
            'values' => Session::get($key)
        ], 200);
    }

    public function download(File $file)
    {
        $fileUpl = storage_path('app' . $file->uploadJobPath . $file->file);

        if (!file_exists($fileUpl)) {
            return redirect()->back();
        }

        return response()->download($fileUpl);
    }

    protected function getKey(Request $request)
    {
        $taskId = (int)$request->get('task_id', 0);

        return 'job.files' . $taskId;
    }
}
