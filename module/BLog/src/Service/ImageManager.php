<?php


namespace Blog\Service;


class ImageManager
{
    private $saveToDir = './public/images/';


    public function getSaveToDir()
    {
        return $this->saveToDir;
    }

    public function getSavedFiles()
    {

        if(!is_dir($this->saveToDir)) {
            if(!mkdir($this->saveToDir)) {
                throw new \Exception('Could not create directory for uploads: ' .
                    error_get_last());
            }
        }


        $files = [];
        $handle  = opendir($this->saveToDir);
        while (false !== ($entry = readdir($handle))) {

            if($entry=='.' || $entry=='..')
                continue;

            $files[] = $entry;
        }


        return $files;
    }

    public function getImagePathByName($fileName)
    {
        // Take some precautions to make file name secure.
        $fileName = str_replace("/", "", $fileName);  // Remove slashes.
        $fileName = str_replace("\\", "", $fileName); // Remove back-slashes.

        // Return concatenated directory name and file name.
        return $this->saveToDir . $fileName;
    }

    // Returns the image file content. On error, returns boolean false.
    public function getImageFileContent($filePath)
    {
        return file_get_contents($filePath);
    }


    public function getImageFileInfo($filePath)
    {
        // Try to open file
        if (!is_readable($filePath)) {
            return false;
        }

        // Get file size in bytes.
        $fileSize = filesize($filePath);

        // Get MIME type of the file.
        $finfo = finfo_open(FILEINFO_MIME);
        $mimeType = finfo_file($finfo, $filePath);
        if($mimeType===false)
            $mimeType = 'application/octet-stream';

        return [
            'size' => $fileSize,
            'type' => $mimeType
        ];
    }

}