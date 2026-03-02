<?php
session_start();// ==> demarer une session 
//si l'utilisateur est deja connecte rediriger vers espace.php
if (!empty($_SESSION['user']) && is_array($_SESSION['user'])) { header('Location: espace.php'); exit;}
$dbHost = 'localhost';
$dbName = 'gestion';
$dbUser = 'root';
$dbPass = '';

$erreur = '';
$anciencontenu = ['email' => ''];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //recupuration des donnees du formulaire :
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    // conserver la valeur de l'email pour reaffichage en cas d'erreur 
    $anciencontenu['email'] = $email;
    //validation des donnees saisies 
    if ($email === '') {
        $erreur = 'Le champ email est requis.';

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreur = 'L\'adresse email est invalide !';

    } elseif ($password === '') {
        $erreur = 'Le champ mot de passe est requis.';
    }
    //si aucune erreur de validation , on ouvre la connnexion et on verifie la presence d'email + passwod 
    if ($erreur === '') {
        try {
            $dsn = "mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4";
            $pdo = new PDO($dsn, $dbUser, $dbPass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
            //verifier que l'email existe 
            $stmt = $pdo->prepare('SELECT pseudo ,nom,email,password FROM inscription Where email = :email');
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$user) { // aucun utilisateur avec cet email 
                $erreur = 'Email ou mot de passe incorrect.';


            } else {
                //verifier le mot de passe hache
                if (!password_verify($password, $user['password'])) {
                    $erreur = 'Email ou mot de passe est incorrect ! . ';
                } else {//authentification reussie : 
                    $_SESSION['user'] = ['pseudo' => $user['pseudo'], 'nom' => $user['nom'], 'email' => $user['email']];
                    header('Location: espace.php');
                    exit;
                }
            }

        } catch (PDOException $e) {
            $erreur = 'Erreur serveur . Ressayer plus tard.';
        }
    }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
</head>
</head>

<body>
    <h1>Inscription</h1>
    <div class="container">
        <?php
        if($erreur !== '') : ?>
        <div class="alert alert-danger" role="alert"><?php echo $erreur; ?></div>
        <?php endif; ?>
        <h2>Veuillez vous authentifier</h2>


        <form method="POST">

            <div class="mb-3">
                <label class="form-label">Email address</label>
                <input type="email" class="form-control" name="email" maxlength="50">

            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="password" maxlength="16">
            </div>

            <button type="submit" class="btn btn-primary">Login!</button>
        </form>
    </div>

</body>

</html>