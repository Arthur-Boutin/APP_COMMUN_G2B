<?php
require_once(PROJECT_ROOT . '/modele/connectToSharedDB.php');
require_once(PROJECT_ROOT . '/modele/user/checkCredentials.php'); 

function updateUserData(int $id, string $prenom, string $nom, string $pseudonyme, string $email): bool
{
    try {
        $pdo = connectToSharedDB();
        $sql = "UPDATE utilisateur SET prenom = :prenom, nom = :nom, pseudonyme = :pseudonyme, email = :email WHERE id_utilisateur = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':pseudonyme', $pseudonyme);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    } catch (PDOException $e) {
        error_log("Database access error in updateUserData: " . $e->getMessage());
        return false;
    }
}

function updateUserPassword(int $id, string $newPasswordHashed): bool
{
    try {
        $pdo = connectToSharedDB();
        $sql = "UPDATE utilisateur SET mot_de_passe = :password WHERE id_utilisateur = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':password', $newPasswordHashed);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    } catch (PDOException $e) {
        error_log("Database access error in updateUserPassword: " . $e->getMessage());
        return false;
    }
}
?>