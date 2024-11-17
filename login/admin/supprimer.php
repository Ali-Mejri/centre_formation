<?php
session_start();

// Vérification de la connexion et des droits administrateur
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Connexion à la base de données
try {
    $db = new PDO('mysql:host=localhost;dbname=center_formation;charset=utf8', 'root', '');
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

if (!empty($_POST)) {
    $user_id = $_POST['id'];

    // Suppression de l'utilisateur
    $sqlQuery = "DELETE FROM users WHERE id = :id";
    $stmt = $db->prepare($sqlQuery);
    $stmt->bindParam(':id', $user_id);
    $stmt->execute();

    echo "Utilisateur supprimé avec succès!";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer un utilisateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>Supprimer un utilisateur</h2>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="id" class="form-label">ID de l'utilisateur à supprimer</label>
            <input type="text" class="form-control" name="id" id="id" required>
        </div>
        <button type="submit" class="btn btn-danger">Supprimer</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
