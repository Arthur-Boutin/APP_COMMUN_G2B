<?php
require_once(__DIR__ . '/../connectToSharedDB.php');

/**
 * @param string $pseudonyme
 * @param string $hashedPassword
 * @param string $prenom
 * @param string $nom
 * @param string $email
 * @return int|null
 */
function insertUser(string $pseudonyme, string $hashedPassword, string $prenom, string $nom, string $email): ?int
{
    try {
        $pdo = connectToSharedDB();
        $sql = "INSERT INTO utilisateur (pseudonyme, mot_de_passe, prenom, nom, email, id_site)
                VALUES (:pseudonyme, :motDePasse, :prenom, :nom, :email, 3);";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':pseudonyme' => $pseudonyme,
            ':motDePasse' => $hashedPassword,
            ':prenom' => $prenom,
            ':nom' => $nom,
            ':email' => $email
        ]);

        $stmt->closeCursor();
        return $pdo->lastInsertId();
    } catch (PDOException $e) {
        // Error executing the query
        $error = $e->getMessage();
        echo mb_convert_encoding("Database access error: $error \n", 'UTF-8', 'UTF-8');
        return null;
    }
}

function verifyUsername(string $pseudonyme): ?bool
{
    try {
        $pdo = connectToSharedDB();
        $sql = "SELECT * FROM `utilisateur` WHERE pseudonyme=:valUsername";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":valUsername", $pseudonyme);
        $bool = $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        return count($results) === 0;

    } catch (PDOException $e) {
        // Error executing the query
        $error = $e->getMessage();
        echo mb_convert_encoding("Database access error: $error \n", 'UTF-8', 'UTF-8');
        return null;
    }
}

function verifyMail(string $email): ?bool
{
    try {
        $pdo = connectToSharedDB();
        $sql = "SELECT * FROM `utilisateur` WHERE email=:valMail";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":valMail", $email);
        $bool = $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        return count($results) === 0;
    } catch (PDOException $e) {
        // Error executing the query
        $error = $e->getMessage();
        echo mb_convert_encoding("Database access error: $error \n", 'UTF-8', 'UTF-8');
        return null;
    }
}