<?php
namespace App\Helper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Cloudinary\Transformation\Delivery;
use Cloudinary\Transformation\Quality;
class ImageManager
{
    public static function upload(string $dir, string $format, $image = null)
    {
        if ($image != null) {
            $imageName = Carbon::now()->toDateString() . "-" . uniqid() . "." . $format;
            if (!Storage::disk('public')->exists($dir)) {
                Storage::disk('public')->makeDirectory($dir);
            }
            Storage::disk('public')->put($dir.'/'.$imageName, file_get_contents($image));
        } else {
            $imageName = 'def.png';
        }

        return $imageName;
    }

    public static function Base64Image(string $dir, string $format, $image = null)
    {   //dd($image);
        if ($image != null) {
            $imageName = Carbon::now()->toDateString() . "-" . uniqid() . "." . $format;
            if (!Storage::disk('public')->exists($dir)) {
                Storage::disk('public')->makeDirectory($dir);
            }
            Storage::disk('public')->put($dir . $imageName, base64_decode($image));
            // Storage::disk('public')->put('member/kyc/'. Carbon::now()->toDateString() . "-" . uniqid() . "1233." .'png', base64_decode($req->image));

        } else {
            $imageName = 'def.png';
        }

        return $imageName;
    }
    public static function Base64update(string $dir, $old_image, string $format, $image = null)
    {
        if (Storage::disk('public')->exists($dir . $old_image)) {
            Storage::disk('public')->delete($dir . $old_image);
        }
        $imageName = ImageManager::Base64Image($dir, $format, $image);
        return $imageName;
    }
    public static function update(string $dir, $old_image, string $format, $image = null)
    {
        if (Storage::disk('public')->exists($dir . $old_image)) {
            Storage::disk('public')->delete($dir . $old_image);
        }
        $imageName = ImageManager::upload($dir, $format, $image);
        return $imageName;
    }

    public static function delete($full_path)
    {
        //dd($full_path);
        if (Storage::disk('public')->exists($full_path)) {
            Storage::disk('public')->delete($full_path);
        }

        return [
            'success' => 1,
            'message' => 'Removed successfully !'
        ];
    }



    public static function imageResize(string $dir, string $format, $image = null, $width = 1360)
    {
        // Check if an image was provided
        if (!$image) {
            return ['error' => 'No image provided'];
        }

        try {
            // Calculate height based on a 16:9 aspect ratio
            // $height = intval($width / 16 * 9);

            // Determine the correct resource type
            $extension = strtolower($image->getClientOriginalExtension());
            $resourceType = in_array($extension, ['jpg', 'png', 'gif', 'bmp']) ? 'image' : ($extension == 'pdf' ? 'raw' : 'auto');

            $cloudinaryImage = Cloudinary::uploadFile($image->getRealPath(), [
                'folder' => $dir,
                'resource_type' => $resourceType,
                'transformation' => [
                    // 'width' => $width,
                    // 'height' => $height,
                    'crop' => 'fill',
                    'quality' => 'auto',
                    // 'format' => $extension
                ]
            ]);
            // dd($cloudinaryImage);
            // Retrieve secure URL and public ID if needed
            $url = $cloudinaryImage->getSecurePath();

            return str_replace("/upload/", "/upload/q_auto/", $url);;

        } catch (\Exception $e) {
            // Handle any exceptions that occur during upload
            return ['error' => $e->getMessage()];
        }
    }


public static function product_image_path($image_type)
{

    return  asset('storage/app/public/'.$image_type);

}

}
