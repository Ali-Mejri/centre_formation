<?php
session_start();

// Vérification si l'utilisateur est connecté et est un admin
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

// Traitement du formulaire de mise à jour
if (!empty($_POST)) {
    // Récupération des données du formulaire
    $id = $_POST['id'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $user_type = $_POST['user_type'];

    // Mise à jour de l'utilisateur dans la base de données
    $sqlQuery = "UPDATE users SET nom = ?, prenom = ?, email = ?, user_type = ? WHERE id = ?";
    $requete = $db->prepare($sqlQuery);
    $requete->execute([$nom, $prenom, $email, $user_type, $id]);

    // Message de succès après mise à jour
    echo "<script>alert('Les informations de l\'utilisateur ont été mises à jour avec succès.'); window.location.href='modifier.php';</script>";
    exit;
}

// Récupération des utilisateurs (formateurs et étudiants)
$sqlQuery = 'SELECT * FROM users WHERE user_type IN ("formateur", "etudiant")';
$requete = $db->prepare($sqlQuery);
$requete->execute();
$users = $requete->fetchAll();
?>
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des utilisateurs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
    <h1>Gestion des utilisateurs</h1>

    <!-- Tableau des utilisateurs -->
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Type</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['id']) ?></td>
                    <td><?= htmlspecialchars($user['nom']) ?></td>
                    <td><?= htmlspecialchars($user['prenom']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['user_type']) ?></td>
                    <td>
                        <!-- Formulaire de modification -->
                        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?= $user['id'] ?>">Modifier</button>

                        <!-- Modal de modification -->
                        <div class="modal fade" id="editModal<?= $user['id'] ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel">Modifier l'utilisateur</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Formulaire de modification simplifié -->
                                        <form action="modifier.php" method="POST">
                                            <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                            <div class="mb-3">
                                                <label for="nom" class="form-label">Nom</label>
                                                <input type="text" class="form-control" id="nom" name="nom" value="<?= htmlspecialchars($user['nom']) ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="prenom" class="form-label">Prénom</label>
                                                <input type="text" class="form-control" id="prenom" name="prenom" value="<?= htmlspecialchars($user['prenom']) ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="user_type" class="form-label">Type</label>
                                                <select class="form-select" id="user_type" name="user_type" required>
                                                    <option value="formateur" <?= $user['user_type'] == 'formateur' ? 'selected' : '' ?>>Formateur</option>
                                                    <option value="etudiant" <?= $user['user_type'] == 'etudiant' ? 'selected' : '' ?>>Étudiant</option>
                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Sauvegarder les modifications</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
