<?php


namespace App\Services;


use Illuminate\Support\Facades\Storage;

class MediaService
{
    public static function uploadMedia($image, $fileName, $path, $preFile)
    {
        if ($preFile){
            unlink(storage_path('app/public/uploads/thumbnail/').$preFile);
        }
        $file = substr($image, strpos($image, ',') + 1);
        $file =  base64_decode($file);
        return Storage::disk('public')->put($path.$fileName, $file);
    }
}
