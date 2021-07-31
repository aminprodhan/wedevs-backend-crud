<?php
namespace App\Traits;
 
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
/**
 * Trait UploadAble
 * @package App\Traits
 */
trait UploadAble
{
    /**
     * @param UploadedFile $file
     * @param null $folder
     * @param string $disk
     * @param null $filename
     * @return false|string
     */
    //(UploadedFile $file
    public function uploadOne($file, $folder = null,
     $filename = null,$width=400,$height=400)
    {
        $random = Str::random(25);
        $name = !is_null($filename) ? $filename : $random;
 
        $path = public_path().$folder;

        $resized_image = \Image::make($file)
                    ->resize($width, $height)
                    ->save($path.''.$name);
        if($resized_image)
            return 1;
        return 0;            
    }
 
    /**
     * @param null $path
     * @param string $disk
     */
    public function deleteOne($path = null, $disk = 'public')
    {
        //Storage::disk($disk)->delete($path);
        if (\File::exists($path)) 
            unlink($path);
    }
}