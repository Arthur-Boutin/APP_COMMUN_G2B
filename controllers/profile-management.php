<?php
require_once '../config/constants.php';
include PROJECT_ROOT . '/config/autoload.php'; 

session_start();

// Check if user is logged in, redirect to login if not
if (!isset($_SESSION['account']) || !isset($_SESSION['account']['id_utilisateur'])) {
    header("Location: log-in.php");
    exit;
}

require_once PROJECT_ROOT . '/modele/user/getUser.php';
require_once PROJECT_ROOT . '/modele/user/updateUser.php';
require_once PROJECT_ROOT . '/modele/user/checkCredentials.php'; 

$errors = array();
$success_message = "";


$userId = $_SESSION['account']['id_utilisateur'];
$currentUser = getUserById($userId); 

if (!$currentUser) {
   
    session_destroy();
    header("Location: log-in.php?error=user_not_found");
    exit;
}


$prenom = $currentUser['prenom'] ?? '';
$nom = $currentUser['nom'] ?? '';
$pseudonyme = $currentUser['pseudonyme'] ?? '';
$email = $currentUser['email'] ?? '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   
    if (isset($_POST['update_profile'])) {
        $prenom_new = test_input($_POST['prenom'] ?? '');
        $nom_new = test_input($_POST['nom'] ?? '');
        $pseudonyme_new = test_input($_POST['username'] ?? ''); 
        $email_new = test_input($_POST['email'] ?? '');

        $hasChanges = false;
        if ($prenom_new !== $prenom) $hasChanges = true;
        if ($nom_new !== $nom) $hasChanges = true;
        if ($pseudonyme_new !== $pseudonyme) $hasChanges = true;
        if ($email_new !== $email) $hasChanges = true;


      
        if (!$prenom_new) { $errors['prenom'] = "Le prénom est obligatoire."; }
        if (!$nom_new) { $errors['nom'] = "Le nom est obligatoire."; }
        if (!$pseudonyme_new) { $errors['pseudonyme'] = "Le pseudonyme est obligatoire."; }
        if (!$email_new) { $errors['email'] = "L'email est obligatoire."; }

        
        if (!lengthPrenom($prenom_new)) { $errors['prenom'] = "Prénom trop long (max 50 chars)."; }
        if (!lengthNom($nom_new)) { $errors['nom'] = "Nom trop long (max 50 chars)."; }
        if (!lengthPseudonyme($pseudonyme_new)) { $errors['pseudonyme'] = "Pseudonyme trop long (max 50 chars)."; }
        if (!validateEmail($email_new)) { $errors['email'] = "Veuillez saisir un email valide."; }

        
        if ($email_new !== $email && !uniqueMail($email_new)) {
            $errors['email_unique'] = "Cet email est déjà utilisé.";
        }
        if ($pseudonyme_new !== $pseudonyme && !uniquePseudonyme($pseudonyme_new)) {
            $errors['pseudonyme_unique'] = "Ce pseudonyme est déjà utilisé.";
        }

        if (empty($errors) && $hasChanges) {
            if (updateUserData($userId, $prenom_new, $nom_new, $pseudonyme_new, $email_new)) {
                $success_message = "Vos informations ont été mises à jour avec succès.";
                
                $_SESSION['account']['prenom'] = $prenom_new;
                $_SESSION['account']['nom'] = $nom_new;
                $_SESSION['account']['pseudonyme'] = $pseudonyme_new;
                $_SESSION['account']['email'] = $email_new;
                $_SESSION['username'] = $pseudonyme_new; 
                
                
                $currentUser = getUserById($userId);
                $prenom = $currentUser['prenom'];
                $nom = $currentUser['nom'];
                $pseudonyme = $currentUser['pseudonyme'];
                $email = $currentUser['email'];

            } else {
                $errors['update_failed'] = "Échec de la mise à jour des informations.";
            }
        } elseif (empty($errors) && !$hasChanges) {
             $success_message = "Aucune modification détectée.";
        }
    }

    
    if (isset($_POST['update_password'])) {
        $current_password = test_input($_POST['current_password'] ?? '');
        $new_password = test_input($_POST['new_password'] ?? '');
        $confirm_new_password = test_input($_POST['confirm_new_password'] ?? '');

       
        if (!password_verify($current_password, $currentUser['mot_de_passe'])) {
            $errors['current_password'] = "Le mot de passe actuel est incorrect.";
        }

        if (!validatePassword($new_password)) {
            $errors['new_password'] = "Le nouveau mot de passe n'est pas valide. Il doit contenir au moins :<br>- Un chiffre.<br>- Une majuscule.<br>- Une minuscule.<br>- Un caractère spécial (#?!@$%^&*-).<br>- Longueur minimale de 8 caractères.";
        }
        if ($new_password !== $confirm_new_password) {
            $errors['confirm_new_password'] = "Les nouveaux mots de passe ne correspondent pas.";
        }
        if ($current_password === $new_password) {
             $errors['new_password_same'] = "Le nouveau mot de passe ne peut pas être identique à l'ancien.";
        }

        if (empty($errors)) {
            $hashed_new_password = hashPassword($new_password);
            if (updateUserPassword($userId, $hashed_new_password)) {
                $success_message = "Votre mot de passe a été mis à jour avec succès.";
                
            } else {
                $errors['password_update_failed'] = "Échec de la mise à jour du mot de passe.";
            }
        }
    }
}


include_once PROJECT_ROOT . '/views/components/header.html';
include_once PROJECT_ROOT . '/views/profile-management.html';
include_once PROJECT_ROOT . '/views/components/footer.html';
?>