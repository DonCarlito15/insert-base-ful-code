<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Inscription</h1>
    <form action="insert.php" method="post">
        <label for="pseudo">Pseudo:</label>
        <input id="pseudo" name="pseudo" type="text" maxlength="30">

        <label for="nom">Nom:</label>
        <input id="nom" name="nom" type="text" maxlength="40">

        <label for="email">Email:</label>
        <input id="email" name="email" type="text" maxlength="50">

        <label for="password">Mot de passe:</label>
        <input id="password" name="password" type="password" maxlength="16">
        <button type="submit">S'inscrire</button>
    </form>
</body>

</html>