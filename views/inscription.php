<?php /* inscription.php */ ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d’inscription - Temp Control Club</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <header>
            <h1>Page d’inscription</h1>
            <p class="subtitle">Remplissez les champs pour vous inscrire</p>
        </header>
        <main>
            <form action="#" method="post">
                <div class="form-group">
                    <label for="email">Email :</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe :</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirmer le mot de passe :</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit" class="form-btn">S'inscrire</button>
            </form>
            <p class="form-footer-text">Déjà un compte ? <a href="connexion.php">Se connecter</a></p>
        </main>
    </div>
</body>
</html>