<?php

$path = "C:\Users\yahia\Desktop\php_course\superglobals.md";

if (file_exists($path)) {
    // $content = file_get_contents($path);
    // $lines = explode("\n", $content);
    // $count = count($lines);


    // for ($i = 0; $i < $count / 2; $i++) {
    //     echo $lines[$i] . "<br>";
    // }

    // $lines =  file($path);
    // foreach ($lines as $line) {
    //     echo $line . "<br>";
    // }

    // echo "<pre>";
    // print_r($lines);
    // echo "</pre>";

    // $superglobal_script = fopen($path, "r");
    // $superglobal_characters = fread($superglobal_script, filesize($path));
    // // replace the \n with <br>
    // $superglobal_characters = str_replace("\n", "<br>", $superglobal_characters);
    // echo filesize($path) . "BYTES";
    // echo "<br>";
    // echo $superglobal_characters;

    // fclose($superglobal_script);



    // writing the date of the modification of the script 
    $new_file_path = "C:\\Users\\yahia\\Desktop\\php_course\\test.html";

    // $html_content = "<html><body><h1>this is a test for the html file writing using PHP with put content function</h1></body></html>";



    // file_put_contents($new_file_path, "this added content using put file put content function", FILE_APPEND | LOCK_EX);
    // $html_file = fopen($new_file_path, "a");
    // fwrite($html_file, "hello ");
    // fwrite($html_file, "world ");
    // fclose($html_file);
    // echo "file content modified successfully!";




    // if (file_exists($new_file_path)) {
    //     echo "File: $new_file_path<br>";
    //     echo "Size: " . filesize($new_file_path) . " bytes<br>";
    //     echo "Last modified: " . date('Y-m-d H:i:s', filemtime($new_file_path)) . "<br>";
    //     echo "Last accessed: " . date('Y-m-d H:i:s', fileatime($new_file_path)) . "<br>";
    //     echo "Permissions: " . substr(sprintf('%o', fileperms($new_file_path)), -4) . "<br>";
    //     echo "Owner: " . fileowner($new_file_path) . "<br>";
    //     echo "Group: " . filegroup($new_file_path) . "<br>";
    // }






    // $info = pathinfo($new_file_path);

    // echo "Directory: " . $info['dirname'] . "\n";      // /home/user/documents
    // echo "Filename: " . $info['basename'] . "\n";      // report.pdf
    // echo "Name: " . $info['filename'] . "\n";          // report
    // echo "Extension: " . $info['extension'] . "\n";    // pdf

    // // You can also get specific parts
    // echo "Just extension: " . pathinfo($new_file_path, PATHINFO_EXTENSION) . "\n";

    // $directory = __DIR__;
    // $files_for_current_folder = scandir($directory);
    // for ($i = 2; $i < count($files_for_current_folder); $i++) {
    //     $file = $files_for_current_folder[$i];
    //     if (is_file($directory . '/' . $file)) {
    //         echo $file . "<br>";
    //     }
    // }
    // // Create directory (including parent directories)
    // if (!is_dir($directory)) {
    //     if (mkdir($directory, 0755, true)) {
    //         echo "Directory created successfully!";
    //     } else {
    //         echo "Failed to create directory!";
    //     }
    // } else {
    //     echo "Directory already exists!";
    // }
    $target_dir = "C:\Users\yahia\Desktop\php_course\media";
    chmod(
        $target_dir,
        0777
    );
}
