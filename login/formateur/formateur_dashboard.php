<?php
session_start();

// Vérifier si l'utilisateur est connecté et est un formateur
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'formateur') {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Formateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h1>Bienvenue Formateur, <?= htmlspecialchars($_SESSION['user_name']) ?>!</h1>
    <p>Voici votre tableau de bord. Vous pouvez gérer les formations et suivre les progrès des étudiants.</p>

    <a href="../logout.php" class="btn btn-danger">Se déconnecter</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
