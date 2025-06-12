<?php
require_once '../config/constants.php';
session_start();

$errors = array();

if (isset($_SESSION['account'])) {
    header("Location: ../index.php");
    exit;
}

if (isset($_POST) && count($_POST) > 0) {
    if (!isset($_POST['email']) || !isset($_POST['password'])) {
        $errors['fields'] = "Veuillez remplir tous les champs";
    }

    $email = htmlentities($_POST['email']);
    $password = htmlentities($_POST['password']);

    include_once("../modele/user/checkLogin.php");
    $account = areCrendentialsCorrect($email, $password);

    if (!$account) {
        $errors['login'] = "Erreur lors de l'identification. Login ($email) et/ou mot de passe incorrects. Si vous venez de vous inscrire, veuillez vérifier votre boîte mail pour valider votre compte.";
    }

    if (empty($errors)) {
        $_SESSION['account'] = $account;
        $_SESSION['username'] = $account['pseudonyme'];

        header("Location: ../index.php");
        exit;
    }
}

include_once '../views/components/header.html';
include_once '../views/log-in.html';
include_once '../views/components/footer.html';