<?php
$message = '';

// when submitting the form, make sure the file field exists before using it
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_FILES['file']) AND $_FILES['file']['error'] == 0) {

        //verifie que le fichier vient bien d'un upload HTTP POST
        if (is_uploaded_file($_FILES['file']['tmp_name'])) {

            //test sur la taille (<200 ko)
            if ($_FILES['file']['size'] <= 204800) {

                //verificarion du mime reel
                $finfo = finfo_open((FILEINFO_MIME_TYPE));
                $type_mime = finfo_file($finfo, $_FILES['file']['tmp_name']);
                finfo_close($finfo);

                $types_autororises = array('image/gif', 'image/png', 'image/jpeg');

                if (in_array($type_mime, $types_autororises, true)) {

                    if (move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/' . basename($_FILES['file']['name']))) {
                        $message = "votre fichier a bien ete envoye!";
                    } else {
                        $message = "Erreur lors du deplacement du fichier.";
                    }

                } else {
                    $message = "Votre fichier doit etre une image de type png /gif/jpeg !";
                }

            } else {
                $message = "Le fichier depasse 200 ko !";
            }

        } else {
            $message = "le fichier n'a pas ete envoye correctement ! ";
        }

    } else {
        $message = "le fichier n'a pas ete envoye correctement ! ";
    }

} else {
    $message = "Erreur lors de l'envoi , veuillez ressayer !";
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


        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>


            <?php if (strpos($message, 'bien ete envoye') !== false): ?>
                <div class="alert alert-success" role="alert"><?php echo $message; ?></div>

            <?php else: ?>
                <div class="alert alert-danger" role="alert"><?php echo $message; ?></div>

            <?php endif; ?>


        <?php endif; ?>

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