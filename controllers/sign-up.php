<?php
require_once '../config/constants.php';

session_start();

$errors = array();

if (isset($_SESSION['account'])) {
    header("Location: index.php");
}

require_once PROJECT_ROOT . '/modele/user/insertUser.php';
require_once PROJECT_ROOT . '/modele/user/checkCredentials.php';
require_once PROJECT_ROOT . '/modele/user/getUser.php';

$pseudonyme = $motDePasse = $prenom = $nom = $email = "";

if (isset($_POST) && count($_POST) > 0) {
    $prenom = test_input($_POST["prenom"]);
    $nom = test_input(data: $_POST["nom"]);
    $pseudonyme = test_input($_POST["username"]);
    $email = test_input($_POST["email"]);
    $motDePasse = test_input($_POST["password"]);
    $motDePasseConfirmed = test_input($_POST["confirm_password"]);

    $validateLengthPrenom = lengthPrenom($prenom);
    $validateLengthNom = lengthNom($nom);
    $validateLengthPseudonyme = lengthPseudonyme($pseudonyme);
    $validatePseudonymeUnique = uniquePseudonyme($pseudonyme);
    $validateEmail = validateEmail($email);
    $validateEmailUnique = uniqueMail($email);
    $validatePassword = validatePassword($motDePasse);
    $hashedPassword = hashPassword($motDePasse);

    if (!$validateEmail) {
        $errors['email'] = "Veuillez saisir un mail valide.";
    }
    if (!$validateEmailUnique) {
        $errors['email'] = "Ce mail existe déjà";
    }
    if (!$validatePassword) {
        $errors['motDePasse'] = "Veuillez saisir un mot de passe valide.</br> Il doit contenir au moins :</br>"
            . '- Un chiffre.</br>'
            . "- Une majuscule.</br>"
            . "- Une minuscule.</br>"
            . "- Un caractère spécial (#?!@$%^&*-).</br>"
            . "- Longueur minimale de 8 caractères.";
    }
    if (!$validateLengthNom) {
        $errors['nom'] = "Veuillez saisir un nom avec moins de 50 caractères";
    }
    if (!$validateLengthPrenom) {
        $errors['prenom'] = "Veuillez saisir un prénom avec moins de 50 caractères";
    }
    if (!$validateLengthPseudonyme) {
        $errors['pseudonyme'] = "Veuillez saisir un pseudonyme avec moins de 50 caractères";
    }
    if (!$validatePseudonymeUnique) {
        $errors['pseudonymeUnique'] = "Ce pseudonyme existe déjà, veuillez en créer un nouveau";
    }
    if ($motDePasse !== $motDePasseConfirmed) {
        $errors['confirmPassword'] = "Les mots de passe ne correspondent pas";
    }

    if (!isset($_POST['prenom'])) {
        $errors['prenom'] = "Le champ est obligatoire";
    }
    if (!isset($_POST['nom'])) {
        $errors['nom'] = "Le champ est obligatoire";
    }
    if (!isset($_POST['username'])) {
        $errors['username'] = "Le champ est obligatoire";
    }
    if (!isset($_POST['email'])) {
        $errors['email'] = "Le champ est obligatoire";
    }
    if (!isset($_POST['password'])) {
        $errors['password'] = "Le champ est obligatoire";
    }


    if (empty($errors)) {
        if ($validateEmail && $validatePassword) {
            $hashedPassword = hashPassword($motDePasse);
            $token = bin2hex(random_bytes(16));
            $result = insertUser($pseudonyme, $hashedPassword, $prenom, $nom, $email);

            $account = getUser($email);
            header("Location: index.php");
            exit;
        }
    }
}

include_once PROJECT_ROOT . '/views/components/header.html';
include_once PROJECT_ROOT . '/views/sign-up.html';
include_once PROJECT_ROOT . '/views/components/footer.html';
