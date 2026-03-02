<?php
session_start();
if (empty($_SESSION['user']) || !is_array($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
//recuperer les donnees utilisateur depuis la session :
$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
    <div class="container">
        <?php
        $dossier = "uploads/";
        $nom_recherche = $user['pseudo'];
        $fichiers = scandir($dossier);
        $trouve = false;   // initialize
        foreach ($fichiers as $fichier) {
            if ($fichier != "." && $fichier != "..") {   // fix operator
                // nom sans extension 
                $nom_sans_extension = pathinfo($fichier, PATHINFO_FILENAME);

                if ($nom_sans_extension === $nom_recherche) {
                    $f = $fichier;
                    $trouve = true;
                    break;
                }
            }
        }
        if ($trouve) {
            echo '<img src="uploads/' . $f . '">';
        } else {
            echo '<img src="avatar2.png">';
        }
        ?>
        <a href="saveAvatar.php" class="btn btn-primary">Modifier l'avatar</a>
        <div class="card">
            <div class="card-header">
                Bienvenue dans votre espace
            </div>
            <div class="card-body">
                <h5 class="card-title"><?php echo "Vous etes connecte en tant que : " . $user['pseudo']; ?></h5>
                <p class="card-text"><?php echo "Email : " . $user['email']; ?></p>
                <a href="logout.php" class="btn btn-primary">Se deconnecter !</a>
            </div>
        </div>
    </div>
</body>
</html>