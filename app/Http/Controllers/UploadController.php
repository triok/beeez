<?php

namespace App\Http\Controllers;

use App\Models\File;
use Session;

class UploadController extends Controller
{
    public function store()
    {
        if (request()->ajax() && request()->hasFile('file') &&
                request()->file('file')->isValid()) {

            $file = request()->file('file');
            $name = time().$file->getClientOriginalName();
            request()->file->storeAs('/public/jobs/upload', $name);

            Session::push('job.files', [
                'file'          => $name,
                'size'          => $file->getSize(),
                'type'          => $file->getMimeType(),
                'original_name' => $file->getClientOriginalName(),
            ]);

            unset($file, $name);

            return response()->json([], 200);
        }
    }

    public function download(File $file)
    {
        $fileUpl = storage_path('app'.$file->uploadJobPath . $file->file);

        if (!file_exists($fileUpl)) {
            return redirect()->back();
        }
        return response()->download($fileUpl);
    }

}
