<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic;
use App\Models\Image;
use ZipArchive;

class ImageController extends Controller
{

    public function all(Request $request) {
        $sortBy = $request->query('sortBy');

        $query = Image::query();

        switch ($sortBy) {
            case 'name':
                $query->orderBy('name');
                break;

            case 'new':
                $query->latest();
                break;

            case 'old':
                $query->oldest();
                break;

            default:
                $query->select('*');
                break;
        }

        $files = $query->get();

        return view('list', ['files' => $files]);
    }

    public function upload(Request $request)
    {
        if (!$request->hasFile('main_slider')) return view('upload', ['status' => 'ERROR', 'message' => 'Ни один файл не был передан']);

        $files = $request->file('main_slider');

        if (count($files) > 5) return view('upload', ['upload' => 'ERROR', 'message' => 'Лимит количества картинок превышен']);

        $validator = Validator::make(
            $request->all(),
            [
                'main_slider.*' => 'image|mimes:jpeg,png,jpg,gif|max:5012'
            ],
            [
                'main_slider.*.image' => 'Файл должен быть изображением.',
                'main_slider.*.mimes' => 'Поддерживаемые форматы изображений: jpeg, png, jpg, gif.',
                'main_slider.*.max' => 'Максимальный размер изображения 2MB.',
            ]
        );

        if ($validator->fails()) {
            return view('upload', ['status' => 'ERROR', 'message' => $validator->messages()->all()[0]]);
        }

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

        return view('upload', ['status' => 'SUCCESS', 'message' => 'Сохранено']);
    }

    public function preview(Request $request)
    {
        $id = $request->query('id');
        $image = Image::find($id);
        if (!$image) abort(404);

        $originalName = storage_path("app/{$image->path}");
        $thumb = ImageManagerStatic::make($originalName)->fit(300, 300);

        return $thumb->response();
    }

    public function download(Request $request)
    {
        $id = $request->query('id');
        $image = Image::find($id);
        if (!$image) abort(404);

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
