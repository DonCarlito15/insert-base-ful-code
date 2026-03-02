<?php
$files = ['name' => '', 'size' => '', 'type' => '', 'tmp_name' => '', 'error' => ''];
// when submitting the form, make sure the file field exists before using it
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $files['name'] = $_FILES['file']['name'];
    $files['size'] = $_FILES['file']['size'];
    $files['type'] = $_FILES['file']['type'];
    $files['tmp_name'] = $_FILES['file']['tmp_name'];
    $files['error'] = $_FILES['file']['error'];
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
        <?php
        if(!in_array('' ,$files,true)){
            echo"Les inforamtions du fichier envoye : <br>";
            echo" nom du fichier : ".$files['name']."<br>";
            echo" type du fichier : ".$files['type']."<br>";
            echo" taille du fichier (en octets) : ".$files['size']."<br>";
            echo" dossier temporaire: ".$files['tmp_name']."<br>";
            echo" code erreur : ".$files['error']."<br>";
        }
        //deplacer le fichier du dossier temporaire vers le dossier uploads (qu'on a cree)
        move_uploaded_file($files['tmp_name'],'uploads/'.basename($files['name']));

        ?>
    </div>
</body>

</html>