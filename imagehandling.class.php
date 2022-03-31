<?php

class imagehandling {

    /**
     * Saves the image to a given folder
     *
     * @param string $encoded The base 64 endcoded image
     * @param string $path The path to save to - defaults to local
     * @return array with filename and extension
     */
    public static function save_image(string $encoded, string $path = './'): array {
        $extension = self::detect_type($encoded);
        $filename = $path . general::get_safe_guid() . '.' . $extension;

        $handle = fopen($filename, 'wb');
        fwrite($handle, base64_decode($encoded));
        fclose($handle);

        return [
            'filename'	=> $filename,
            'extension'	=> $extension
        ];
    }

    /**
     * Detects the MIME type of the encoded image and converts it to extension
     * This may need to be split into 2 routines
     *
     * @param string $encoded The Base64 Encoded string to check
     * @return Either string or null
     */
    public static function detect_type(string $encoded) {
        $decoded = base64_decode($encoded);
        $handle = finfo_open();
        $info = finfo_buffer($handle, $decoded, FILEINFO_MIME_TYPE);
        finfo_close($handle);

        $datatypes = [
            'image/png'		=> 'png',
            'image/jpeg'	=> 'jpg',
            'image/bmp'		=> 'bmp',
            'image/gif'		=> 'gif',
            'image/tiff'	=> 'tif'
        ];

        return ($datatypes[$info]) ? $datatypes[$info] : '';
    }

}