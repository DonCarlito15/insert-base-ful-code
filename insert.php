<?php
$dbHost = 'localhost';
$dbName = 'gestion';
$dbUser = 'root';
$dbPass = '';

try{
    $dsn = "mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4";
    $pdo = new PDO($dsn, $dbUser, $dbPass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
}
catch(PDOException $e){

    echo '!doctype html><html lang="fr"><head><meta charset="UTF-8"><title>Erreur</title></head><body>';
    echo '<p style="color:red;">Erreur de connexion à la base de données .</p>';
    echo '</body></html>';
    exit;
}
//Récupérer les données du formulaire
$pseudo = $_POST['pseudo'];
$nom = $_POST['nom'];
$email = $_POST['email'];
$password = $_POST['password'];
try{
    //1)Verifier si le pseudo existe
    $stmt = $pdo->prepare('SELECT pseudo FROM inscription WHERE pseudo = :p');
    $stmt->execute([':p' => $pseudo]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);//recupérer une seule ligne sous forme de tableau associatif
                                          //PDO: FETCH_NUM ==> Recupérer les données sous forme de tableau indexé numériquement

    if($row){
        echo '!doctype html><html lang="fr"><head><meta charset="UTF-8"><title>Inscripion</title></head><body>';
        echo '<p style="color:red;">Le pseudo existe déjà. Choisissez un autre pseudo.</p>';
        echo '</body></html>';
        exit;
    }

    //2)verifier si l'email existe
    $stmt = $pdo->prepare('SELECT email FROM inscription WHERE email = :m');
    $stmt->execute([':m' => $email]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if($row){
        echo '<!doctype html><html lang="fr"><head><meta charset="UTF-8"><title>Inscripion</title></head><body>';
        echo '<p style="color:red;">Le email existe déjà. Choisissez un autre Email.</p>';
        echo '</body></html>';
        exit;
    }
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);


    //password_default ==>algorithme de hachage le plus  recent selon la version de PHP utilisée
    $sql = 'INSERT INTO inscription values (:p ,:n,:e,:pw)';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
    ':p' => $pseudo, 
    ':n' => $nom,
    ':e' => $email, 
    ':pw' => $passwordHash
    ]);

    echo '<!doctype html><html lang="fr"><head><meta charset="UTF-8"><title>Inscription reussie</title></head><body>';
    echo '<h1>Inscription </h1>';
    echo '<p style="color:green;">Inscription sauvgardee avec succes.</p>';
    echo '</body></html>';
    exit;
}
catch(PDOException $e){
    echo '!doctype html><html lang="fr"><head><meta charset="UTF-8"><title>Erreur</title></head><body>';
    echo '<p style="color:red;">Une erreur est survenue lors de l\'inscription. Ressayez plus tard.</p>';
    echo '</body></html>';
    exit;
}