<?php /* connexion.php */ ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de connexion - Temp Control Club</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <header>
            <h1>Page de connexion</h1>
            <p class="subtitle">Veuillez vous connecter pour accéder à votre espace</p>
        </header>
        <main>
            <!-- action="#" 表示提交到当前页面，这在后端开发时会修改 -->
            <form action="#" method="post">
                <div class="form-group">
                    <label for="email">Email :</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe :</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="form-btn">Se connecter</button>
            </form>
            <p class="form-footer-text">Pas encore de compte ? <a href="inscription.php">Créer un compte</a></p>
        </main>
    </div>
</body>
</html>