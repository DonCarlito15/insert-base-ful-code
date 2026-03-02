<?php
$files = ['name' => '', 'size' => '', 'type' => '', 'tmp_name' => '', 'error' => ''];

// make sure the session is started and the user is logged in
session_start();
if (empty($_SESSION['user']) || !is_array($_SESSION['user'])) {
    // no user, redirect to login rather than allowing an anonymous upload
    header('Location: login.php');
    exit;
}

// when submitting the form, make sure the file field exists before using it
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {

    // renommer l'image du profile par le pseudo de l'utilisateur 
    $pseudo = $_SESSION["user"]["pseudo"];
    $infos  = pathinfo($_FILES['file']['name']);
    $extension = strtolower($infos['extension']);

    // build target directory and filename
    $targetDir = __DIR__ . '/uploads';
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    // nouveau nom = pseudo + extension
    $nouveau_nom = $pseudo . "." . $extension;
    $destination  = $targetDir . '/' . $nouveau_nom;

    if (move_uploaded_file($_FILES['file']['tmp_name'], $destination)) {
        // store the relative path in session (could also be stored in DB)
        $_SESSION['user']['avatar'] = 'uploads/' . $nouveau_nom;
    }

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