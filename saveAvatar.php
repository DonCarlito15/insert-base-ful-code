<?php
$files = ['name' => '', 'size' => '', 'type' => '', 'tmp_name' => '', 'error' => ''];
session_start(); // 1- start a session to access session variables
// when submitting the form, make sure the file field exists before using it
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {

    // renommer l'image du profile par le pseudo de l'utilisateur 
    $pseudo= $_SESSION["user"]["pseudo"];
    $infos=pathinfo($_FILES['file']['name']);
    $extension = $infos['extension'];
    //nouveau nom = pseudo + extension
    $nouveau_nom = $pseudo . "." . $extension;
    // store the file inside the uploads directory
    $destination = "uploads/" . $nouveau_nom;
    move_uploaded_file($_FILES['file']['tmp_name'], $destination);
    // keep the session path in sync with the actual location
    $_SESSION['user']['avatar'] = $destination; // 2- remember the avatar storage

    header("location: espace.php");
    exit;
} 
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet">
</head>

<body>
    <div class="container">
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="formFileLg" class="form-label">Choisir un fichier ... </label>
                <input class="form-control form-control-lg" id="formFileLg" name="file" type="file">
            </div>
            <button type="submit" class="btn btn-primary">Envoyer</button>
        </form>
      
    </div>
</body>

</html>