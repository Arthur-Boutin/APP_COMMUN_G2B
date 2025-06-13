<?php
require_once "getUser.php";

function areCredentialsCorrect(string $email, string $password): array|bool
{
    $user = getUser($email);

    if (!is_null($user)) {
        if (password_verify($password, $user['mot_de_passe'])) {
            return $user;
        }
    }
    return false;
}