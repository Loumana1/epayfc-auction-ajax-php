<?php

require_once 'framework/Configuration.php';

class ImageProcessor {


     public static function process_item_upload(string $tmp_path, string $dest_dir, string $base_name): string {

        if (!extension_loaded('gd')) {
            throw new InvalidArgumentException('GD pas actif');
        }


        $max_size = (int) Configuration::get('max_item_picture_size', '5242880');


        if (filesize($tmp_path) > $max_size) {
            throw new InvalidArgumentException('Fichier trop grand');
        }

        if (!is_dir($dest_dir)) {
            mkdir($dest_dir, 0755, true);
        }

        $quality = (int) Configuration::get('item_picture_jpeg_quality', '85');
        $main_w  = (int) Configuration::get('item_picture_max_width', '1080');
        $main_h  = (int) Configuration::get('item_picture_max_height', '1080');
        $thumb_w = (int) Configuration::get('item_thumbnail_max_width', '360');
        $thumb_h = (int) Configuration::get('item_thumbnail_max_height', '360');

        $main_path  = rtrim($dest_dir, '/') . '/' . $base_name . '.jpg';
        $thumb_path = rtrim($dest_dir, '/') . '/' . $base_name . '_thumbnail.jpg';

        self::compress_and_resize_to_jpeg($tmp_path, $main_path, $main_w, $main_h, $quality);
        self::compress_and_resize_to_jpeg($tmp_path, $thumb_path, $thumb_w, $thumb_h, $quality);

        return $main_path;
    }

    public static function compress_and_resize_to_jpeg( string $source_path, string $destination_path,
        int $max_width,
        int $max_height,
        int $quality = 85
    ): void {


        $info = getimagesize($source_path);

        if ($info === false) {
            throw new InvalidArgumentException('cette image est invalide');
        }

        $mime = $info['mime'];
        $source = match ($mime) {
            'image/jpeg' => imagecreatefromjpeg($source_path),
            'image/png'  => imagecreatefrompng($source_path),
            'image/gif'  => imagecreatefromgif($source_path),
            'image/webp' => imagecreatefromwebp($source_path),
            default => false,
        };

        if ($source === false) {
            throw new InvalidArgumentException('type image  pas supporté');
        }




        $width  = imagesx($source);
        $height = imagesy($source);

        $ratio = min($max_width / $width, $max_height / $height, 1.0);

        $new_width  = max(1, (int) round($width * $ratio));
        $new_height = max(1, (int) round($height * $ratio));

        $resized = imagecreatetruecolor($new_width,   $new_height);


        imagecopyresampled( $resized, $source, 0, 0, 0, 0,
            $new_width, $new_height,
            $width, $height
        );

        imagejpeg($resized, $destination_path, $quality);

        imagedestroy($source);
        imagedestroy($resized);
    }

    public static function delete_files(string $main_path): void
    {
        if ($main_path !== '' && file_exists($main_path)) {
            unlink($main_path);
        }
        $thumb = preg_replace('/\.jpg$/i', '_thumbnail.jpg', $main_path);
        if ($thumb !== '' && file_exists($thumb)) {
            unlink($thumb);
        }
    }
}