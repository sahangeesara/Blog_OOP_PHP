<?php
include(__DIR__ .'/../config/app.php');

class Controller 
{

    public $conn;
    //constructer function
    public function __construct()
    {
        $db = new DbConnection;
        $this->conn = $db->conn;



    }

    // Image Upload
    public function image($img, $existingImagePath = '')
    {

        if (empty($img) || !is_array($img) || !isset($img['tmp_name']) || $img['tmp_name'] === '') {
            return $existingImagePath;
        }

        if (!is_dir('images')) {
            mkdir('images');
        }

        $fileName = isset($img['name']) ? basename($img['name']) : '';
        if ($fileName === '') {
            return $existingImagePath;
        }

        $relativePath = 'images/' . $this->randomString(8) . '/' . $fileName;
        $absolutePath = __DIR__ . '/../' . $relativePath;

        if (!is_dir(dirname($absolutePath))) {
            mkdir(dirname($absolutePath), 0777, true);
        }

        if (move_uploaded_file($img['tmp_name'], $absolutePath)) {
            return $relativePath;
        }

        return $existingImagePath;
    }
    
    public function randomString($n)
    {
        return time();
    }
}
?>