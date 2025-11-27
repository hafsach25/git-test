<?php
session_start();
include "auth.php";

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

$auth = new Auth();
$redirect = $auth->login($email, $password);
if ($redirect) {
    header("Location: $redirect");
    exit;
} else {
  
   $error = "Email ou mot de passe incorrect.";
   header("Location: ../../BEEX/frontend/authentification/login.php?error=" . urlencode($error));
    exit;
}
