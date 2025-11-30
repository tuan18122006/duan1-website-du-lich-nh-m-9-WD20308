<?php
require_once __DIR__ . '/env.php';

function connectDB() {
    try {
        $conn = new PDO(
            "mysql:host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_NAME.";charset=utf8",
            DB_USERNAME,
            DB_PASSWORD
        );

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $conn;

    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

function uploadFile($file, $folderSave){
    $filename = $file['name'];
    $tmp = $file['tmp_name'];

    $newName = $folderSave . rand(10000, 99999) . "_" . $filename;
    $fullPath = PATH_ROOT . $newName;

    if (move_uploaded_file($tmp, $fullPath)) {
        return $newName;
    }
    return null;
}

function deleteFile($file){
    $fullPath = PATH_ROOT . $file;

    if (file_exists($fullPath)) {
        unlink($fullPath);
    }
}


?>