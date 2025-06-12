<?php

require_once('insertUser.php');

if (!empty($_POST)) {
    if (isset($_POST['fonction'])) {
        $function = $_POST['fonction'];
        unset($_POST['fonction']);
        echo $function($_POST);
    }
}

function test_input($data): string
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function validateEmail($email): bool
{
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    } else {
        return false;
    }
}

function uniqueMail($email): bool
{
    if (verifyMail($email)) {
        return true;
    } else {
        return false;
    }
}

function uniqueMailJSON($email): string
{
    if (verifyMail($email['email'])) {
        return json_encode(['uniqueEmail' => 'true']);
    } else {
        return json_encode(['uniqueEmail' => 'false']);
    }
}

function validateTelephone($telephone): bool
{
    if (empty($telephone)) {
        return true;
    }
    if (preg_match('#^0[0-9]{9}$#', $telephone)) {
        return true;
    } else {
        return false;
    }
}

function hashPassword($motDePasse): string
{
    $hashedPassword = password_hash($motDePasse, PASSWORD_DEFAULT);
    return $hashedPassword;
}


function validatePassword($motDePasse): bool
{
    if (preg_match('/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-.]).{8,255}$/', $motDePasse)) {
        return true;
    } else {
        return false;
    }
}

function lengthNom($nom): bool
{
    if (strlen($nom) < 50) {
        return true;
    } else {
        return false;
    }
}

function lengthPrenom($prenom): bool
{
    if (strlen($prenom) < 50) {
        return true;
    } else {
        return false;
    }
}

function lengthPseudonyme($pseudonyme): bool
{
    if (strlen($pseudonyme) < 50) {
        return true;
    } else {
        return false;
    }
}

function uniquePseudonyme($pseudonyme): bool
{
    if (verifyUsername($pseudonyme)) {
        return true;
    } else {
        return false;
    }
}

function uniquePseudonymeJSON($pseudonyme): string
{
    if (verifyUsername($pseudonyme['pseudonyme'])) {
        return json_encode(['uniquePseudonyme' => 'true']);
    } else {
        return json_encode(['uniquePseudonyme' => 'false']);
    }
}