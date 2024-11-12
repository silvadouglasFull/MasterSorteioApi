<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class fileManeger
{
    /**
     * Convert a file to base64 using Laravel's Storage class.
     *
     * This function retrieves the content of the specified file using Laravel's Storage class
     * and converts it to a base64-encoded string.
     *
     * @param string $filePath The path of the file to convert.
     * @return string|false The base64-encoded string on success, or false if the file does not exist.
     */
    public function fileToBase64($filePath, $driver = "")
    {
        try {
            // Check if the file exists
            if (Storage::disk($driver === "" ? env("FILESYSTEM_DRIVER") : $driver)->exists(str_replace("//", "/", $filePath))) {
                // Get the content of the file
                $fileContent = Storage::disk($driver === "" ? env("FILESYSTEM_DRIVER") : $driver)->get(str_replace("//", "/", $filePath));
                // Convert the file content to base64
                $base64 = base64_encode($fileContent);
                return $base64;
            }
            return false; // File not found
        } catch (\Throwable $th) {
            Log::error($th);
            return false;
        }
    }
}
