<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multiple File Upload</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1>Multiple File Upload Showcase</h1>

    <div class="upload-form">
        <form action="./details.php" method="post" enctype="multipart/form-data">
            <p>Select multiple files to upload:</p>
            <input type="file" name="uploaded_files[]" id="uploaded-file" multiple accept="png,jpg,jpeg,gif,bmp" />
            <input type="submit" name="submit" value="Upload File">
        </form>
    </div>

    <div class="files-info-card">

    </div>
</body>

</html>