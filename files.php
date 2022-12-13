<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <title>Nahrávání souborů</title>
</head>
</html>

<?php
$allowedFormats = array('jpg', 'jpeg', 'png', 'wav', 'mp3', 'gif', 'mp4');
if(!$_FILES){ showForm(false, "");   return; }
if($_FILES["file"]["error"]!= 0) {showForm(true, "Something went wrong"); return;}

$targetDir = "uploads/";
$targetFile = $targetDir . basename($_FILES["file"]["name"]);
$fileType = strtolower(pathinfo($targetFile,PATHINFO_EXTENSION));


if (!checkFile($targetFile, $fileType, $allowedFormats )) { showForm(true, "Something was wrong with the file"); return; }
if (!move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) { showForm(true, "Upload failed"); return; }

showFile($targetFile, $fileType);

#region Functions
function checkFile($targetFile, $fileType, $allowedFormats){
    return !file_exists($targetFile) && $_FILES["file"]["size"] < 8000000 && in_array($fileType, $allowedFormats);
}
function showForm($hasError, $errorMessage){
    $errorText = $hasError ? $errorMessage : "";
    echo "    
    <div class='container h-100'>
        <div class='row h-100 '>
          <div class='col-lg-6'>
                <h1>$errorText</h1>
                <form action='files.php' method='post' enctype='multipart/form-data'>
                    <label for='formFile' class='form-label'>Upload file</label>
                    <input class='form-control' type='file' id='formFile' name='file'>
                    <button type='submit' name='submit'>Upload</button>
                </form>
          </div> </div> </div>   ";
}

function showFile($targetFile, $fileType){
    $show = "<div class='container h-100'>  <div class='row h-100 '>  <div class='col-lg-6'>";

    if($fileType == 'jpg' || $fileType == 'jpeg' || $fileType == 'png' || $fileType == 'gif')
        $show .= '<img src= "'.$targetFile.'">';
    else if ($fileType == 'wav' || $fileType == 'mp3')
        $show .= '<audio controls autoplay>  <source src="'.$targetFile .'" type="audio/mpeg"> </audio>';
    else
        $show .= '<video width="500" height="500" autoplay>   <source src="'.$targetFile .'" type="video/mp4"> </video>';

    $show .= '<a href="files.php"><button type="button" class="btn btn-dark">Go back</button></a>  </div> </div> </div>';
    echo $show;
}
#endregion
?>

