<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImageUploadRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic;
use App\Models\Image;
use ZipArchive;

class ImageController extends Controller
{

    public function all(Request $request) {
        $sortBy = $request->query('sortBy', '');
        $files = Image::query()->sortBy($sortBy)->get();

        return view('list', ['files' => $files]);
    }

    public function upload(ImageUploadRequest $request)
    {
        $files = $request->file('main_slider');

        $images = [];

        foreach ($files as $file) {

            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();

            $filename = Str::lower(Str::slug(pathinfo($originalName, PATHINFO_FILENAME)));
            $fileNameFull = "{$filename}.{$extension}";

            $i = 1;
            while (file_exists(storage_path('app/public/images/' . $fileNameFull))) {
                $fileNameFull = "{$filename}-{$i}.{$extension}";
                $i++;
            }
            $path = $file->storeAs('public/images', $fileNameFull);

            $images[] = [
                'name' => $fileNameFull,
                'path' => $path
            ];
        }

        Image::insert($images);

        return redirect('/list')->with('message', 'Загружено');
    }

    public function preview(Request $request)
    {
        $id = $request->query('id');
        $image = Image::findOrFail($id);

        $originalName = storage_path("app/{$image->path}");
        $thumb = ImageManagerStatic::make($originalName)->fit(300, 300);

        return $thumb->response();
    }

    public function download(Request $request)
    {
        $id = $request->query('id');
        $image = Image::findOrFail($id);

        $imagePath = storage_path("app/{$image->path}");

        // Создание временного каталога для сохранения временных файлов
        $tempDir = tempnam(sys_get_temp_dir(), 'zip');
        unlink($tempDir);
        mkdir($tempDir);

        $zip = new ZipArchive();
        $zipFileName = "{$tempDir}/{$image->name}.zip";

        if ($zip->open($zipFileName, ZipArchive::CREATE) !== true) {
            abort(500, 'Не удалось создать архив');
        }

        $zip->addFile($imagePath, $image->name);
        $zip->close();

        return response()->download($zipFileName)->deleteFileAfterSend();
    }
}
