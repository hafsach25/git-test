<?php
include __DIR__ . "/auth.php";


$email = strtolower(trim($_POST['email'] ?? ''));
$password = $_POST['password'] ?? '';
//var_dump($_POST);
//exit;

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
?>

