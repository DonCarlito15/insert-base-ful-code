<?php
$dbHost = 'localhost';
$dbName = 'gestion';
$dbUser = 'root';
$dbPass = '';

$erreur = '';
$success = '';

// tableau pour conserver les anciennes valeurs
$anciencontenu = [
  'pseudo' => '',
  'nom' => '',
  'email' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  //Recupuration 
  $pseudo = isset($_POST['pseudo']) ? trim($_POST['pseudo']) : '';
  $nom = isset($_POST['nom']) ? trim($_POST['nom']) : '';
  $email = isset($_POST['email']) ? trim($_POST['email']) : '';
  $password = isset($_POST['password']) ? trim($_POST['password']) : '';

  // stockage pour reaffichage
  $anciencontenu['pseudo'] = $pseudo;
  $anciencontenu['nom']    = $nom;
  $anciencontenu['email']  = $email;

  // Verifier que les champs sont remplis . 
  if ($pseudo === '') {
    $erreur = 'Le champ pseudo est requis !';
  } elseif ($nom === '') {
    $erreur = 'Le champ nom est requis !';
  } elseif ($email === '') {
    $erreur = 'Le champ email est requis !';
  } elseif ($password === '') {
    $erreur = 'Le champ password est requis !';
  }
  // verifier format email
  elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $erreur = "Format d'email invalide !";
  }
  // verifier longueur du mot de passe
  elseif (strlen($password) < 6) {
    $erreur = "Mot de passe trop court (min 6 caractères)";
  }

  //si aucune erreur de validation , on ouvre la connexion et on verifie les doublons (pseudo et email) et l'inscription
  if ($erreur === '') {
    try {
      $dsn = "mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4";
      $pdo = new PDO($dsn, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
      ]);
      
      // verifier le pseudo (le pseudo ne doit pas exister);
      $stmt = $pdo->prepare("SELECT COUNT(*) FROM inscription WHERE pseudo = :p");
      $stmt->execute([':p' => $pseudo]);
      $countPseudo = $stmt->fetchColumn();

      if ($countPseudo > 0) {
        $erreur = 'Le pseudo existe deja . Veuillez choisirir un autre pseudo !';
      } else {
        //2} verifier si l'email existe - execute seulement si le pseudo est libre 
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM inscription WHERE email = :m');
        $stmt->execute([':m' => $email]);
        $countEmail = $stmt->fetchColumn();

        if ($countEmail > 0) {
          $erreur = 'L\'email est deja utilise. Utiliser une autre adresse.';
        } else {
          // Insertion de l'utilisateur
          $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

          $stmt = $pdo->prepare("
            INSERT INTO inscription (pseudo, nom, email, password) 
            VALUES (:p, :n, :m, :pw)
          ");

          $stmt->execute([
            ':p' => $pseudo,
            ':n' => $nom,
            ':m' => $email,
            ':pw' => $hashedPassword
          ]);

          $success = 'Inscription reussie ! . <a href="login.php">Se connecter </a>';

          // reset anciencontenu après succès
          $anciencontenu = [
            'pseudo' => '',
            'nom' => '',
            'email' => ''
          ];
        }
      }

    } catch (PDOException $e) {
      $erreur = 'Un erreur est survenue lors de L\'inscription . Veuillez ressayer plus tard !';
    }
  }
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Page d'inscription de l'ecole IISGA</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<h1>Inscription</h1>

<div class="container">

  <!-- Affichage des messages -->
  <?php if ($success !== ''): ?>
    <div class="alert alert-success"><?= $success ?></div>
  <?php elseif ($erreur !== ''): ?>
    <div class="alert alert-danger"><?= $erreur ?></div>
  <?php endif; ?>

  <form method="POST">

    <div class="mb-3">
      <label class="form-label">Pseudo</label>
      <input type="text" class="form-control" name="pseudo" maxlength="30"
        value="<?= htmlspecialchars($anciencontenu['pseudo']) ?>">
    </div>

    <div class="mb-3">
      <label class="form-label">Nom</label>
      <input type="text" class="form-control" name="nom" maxlength="40"
        value="<?= htmlspecialchars($anciencontenu['nom']) ?>">
    </div>

    <div class="mb-3">
      <label class="form-label">Email address</label>
      <input type="email" class="form-control" name="email" maxlength="50"
        value="<?= htmlspecialchars($anciencontenu['email']) ?>">
    </div>

    <div class="mb-3">
      <label class="form-label">Password</label>
      <input type="password" class="form-control" name="password" maxlength="16">
    </div>

    <button type="submit" class="btn btn-primary">S'inscrire</button>
  </form>
</div>

</body>
</html>