<?php
define("MAX_SIZE", 200000);
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit"])) {

    echo "<pre>";
    print_r($_FILES["uploaded_files"]);
    echo "</pre>";
    echo __DIR__;
    if (file_exists(__DIR__ . "/images/")) {
        $target_dir = __DIR__ . "/images/";
    } else {
        if (!mkdir(__DIR__ . "/images/")) {
            die("Could not create upload directory.<br>");
        }
        $target_dir = __DIR__ . "/images/";
    }
    for ($i = 0; $i < count($_FILES["uploaded_files"]["name"]); $i++) {
        if ($_FILES["uploaded_files"]["error"][$i] === UPLOAD_ERR_OK && $_FILES["uploaded_files"]["size"][$i] <= MAX_SIZE) {
            if (
                move_uploaded_file(
                    $_FILES["uploaded_files"]["tmp_name"][$i],
                    $target_dir . $_FILES["uploaded_files"]["name"][$i]
                )
            ) {
                echo "File" . $_FILES["uploaded_files"]["name"][$i] . "uploaded successfully.<br>";
            } else {
                echo "Error uploading file" . $_FILES["uploaded_files"]["name"][$i] . " <br>.";
            }
        } elseif ($_FILES["uploaded_files"]["size"][$i] > MAX_SIZE) {

            echo "Error uploading file " . $_FILES["uploaded_files"]["name"][$i] . " due to being over the max size of " . MAX_SIZE . " bytes.<br>";
        }
    }



    // a single file handling
    // copy the temporary file into the uplaoded directory with its proper extension
    // $filename = $_FILES["uploaded_file"]["name"];
    // $extension = pathinfo($filename, PATHINFO_EXTENSION);
    // $filename_basename = pathinfo($filename, PATHINFO_FILENAME);
    // echo $filename_basename . "." . $extension . "\n";
    // $accepted_files = ["png", "jpg", "gif", "bmp"];
    // if (in_array(strtolower($extension), $accepted_files)) {
    //     move_uploaded_file($_FILES["uploaded_file"]["tmp_name"], $target_dir . "uploaded_image_" . date("d-m-Y") . "." . $extension);
    // } else {
    //     echo "The file type is not accepted.\n";
    // }


    sleep(1);
    echo "program upload finished<br>";
} else {
    echo "No file uploaded yet<br>";
}



// uploads dir deletion
// $upload_dir = __DIR__ . "/images/";
// if (file_exists($upload_dir)) {
//     $uploaded_images = scandir($upload_dir);
//     foreach ($uploaded_images as $image) {
//         if (is_file($image)) {
//             unlink($upload_dir . $image);
//             echo "Image $image deleted successfully.<br>";
//         }
//     }
//     echo count($uploaded_images) > 0 ? "done deleting all the imagess" : "no images to delete";
//     rmdir($upload_dir);
// }
