<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class UploadService
{
    const PRODUCTS = 'products';

    public static function saveFile($file, $folder)
    {
        $newName = Str::uuid() . "." . $file->getClientOriginalExtension();

        $file->storeAs("public/$folder", $newName);

        return "$folder/$newName";
    }

    public static function saveImageBase64($imageBase64, $folder)
    {
        $newName = Str::uuid() . ".jpg";

        Storage::disk('public')->put("$folder/$newName", base64_decode($imageBase64));

        return "$folder/$newName";
    }

    public static function deleteFile($file)
    {
        Storage::disk('public')->delete($file);
    }

    public static function getUrl($arquivo)
    {
        return url('storage/' . $arquivo);
    }
}
