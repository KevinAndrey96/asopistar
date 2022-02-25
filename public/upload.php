<?php
    $targetDir = Sprintf('storage/%s/', $_POST['path']);
    $targetFile = Sprintf('%s%s', $targetDir, basename($_FILES["image"]["name"]));
    $uploadOk = true;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    /*if(isset($_FILE["image"])) {
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }*/

    // Check if file already exists
    /*if (file_exists($targetFile)) {
      echo "Sorry, file already exists.";
      $uploadOk = 0;
    }*/

    // Check file size
    /*if ($_FILES["image"]["size"] > 500000) {
      echo "Sorry, your file is too large.";
      $uploadOk = 0;
    }*/

    // Allow certain file formats
    /*if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
      echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
      $uploadOk = 0;
    }*/

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk) {
        (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) ? $message = "The file " . htmlspecialchars( basename( $_FILES["image"]["name"])) . " has been uploaded." : $message =  'ERROR uploading the file';
        echo $message;
    }
?>