<?php


namespace App\Services;


use Illuminate\Support\Facades\Storage;

class MediaService
{
    public static function uploadMedia($image, $fileName, $path, $preFile)
    {
        if ($preFile){
            unlink(storage_path('app/public/'.$path).$preFile);
        }
        $file = substr($image, strpos($image, ',') + 1);
        $file =  base64_decode($file);
        return Storage::disk('public')->put($path.$fileName, $file);
    }

    /**
     * This function updates the new file, deletes the old one
     * @param $file
     * @param $path
     * @param $preFile
     * @param $fileName
     * @return string
     */
    public static function saveMedia($file, $path, $preFile, $fileName)
    {
        try {
            if ($file) {
                if ($preFile !="null" && $preFile) {
                    Storage::disk('public')->delete($path . $preFile);
                }
                $fileName = $fileName.'_'.time().'_'.$file->getClientOriginalName();
                Storage::disk('public')->put($path. $fileName, file_get_contents($file));
                $mediaName = $fileName;
            }else{
                $mediaName = $preFile;
            }
            return $mediaName;
        }catch(\Exception $exception){
            dd($exception);
        }
    }
}
