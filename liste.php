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
    die('Erreur de connexion à la base de données.');//afficher un message + exit(arret du script)
}
//Requete pour recuperer les inscrits
$sql = "SELECT pseudo, nom, email FROM inscription ORDER BY nom";
$stmt = $pdo->query($sql);
$inscrits = $stmt->fetchAll(PDO::FETCH_ASSOC);//fetchAll ==>recuperer toutes les lignes du resultat de la requete
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <title>Liste des inscrits</title>
</head>
<body>
   <h3>Liste des inscrits</h3>
   <p>Nombre d'inscrits : <strong><?php echo count ($inscrits); ?></strong></p>

   <?php if (empty($inscrits)) : ?>
    <p>Aucun inscrit trouve.</p>
    <?php else : ?>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Pseudo</th>
                <th scope="col">Nom</th>
                <th scope="col">Email</th>
            </tr>
        </thead>
        <tbody>

            <?php foreach ($inscrits as $row) : ?>
            <tr>
                <td><?php echo $row['pseudo']; ?></td>
                <td><?php echo $row['nom']; ?></td>
                <td><?php echo $row['email']; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</body>
</html>