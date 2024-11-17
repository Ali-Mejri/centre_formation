<?php
include('db.php');
session_start();

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!empty($email) && !empty($password)) {
        try {
            $stmt = $conn->prepare("SELECT id, nom, prenom, password, user_type FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['nom'] . ' ' . $user['prenom'];
                    $_SESSION['user_type'] = $user['user_type'];

                    if ($user['user_type'] == 'admin') {
                        header('Location: admin/admin_dashboard.php');
                    } elseif ($user['user_type'] == 'formateur') {
                        header('Location: formateur/formateur_dashboard.php');
                    } elseif ($user['user_type'] == 'etudiant') {
                        header('Location: etudiant/etudiant_dashboard.php');
                    } else {
                        $error_message = "Rôle utilisateur inconnu.";
                    }
                    exit;
                } else {
                    $error_message = "Mot de passe incorrect.";
                }
            } else {
                $error_message = "Aucun utilisateur trouvé avec cet email.";
            }
        } catch (PDOException $e) {
            $error_message = "Erreur : " . $e->getMessage();
        }
    } else {
        $error_message = "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
/* Style du bouton de retour en haut à gauche */
.back-to-left {
    position: fixed;
    top: 20px;
    left: 20px;
    z-index: 99;
    background-color: var(--primary);
    color: var(--light);
    border-radius: 50%;
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: background-color 0.3s ease, transform 0.3s ease;
}

.back-to-left:hover,
.back-to-left:focus {
    background-color: darken(var(--primary), 10%);
    transform: scale(1.1);
}

.back-to-left i {
    font-size: 20px;
}

@media (max-width: 768px) {
    .back-to-left {
        width: 45px;
        height: 45px;
    }
}


    </style>
</head>
<body>


<a href="../index.html" class="btn btn-lg btn-primary btn-square back-to-left" role="button" aria-label="Retour à la page précédente">
    <i class="bi bi-arrow-left" aria-hidden="true"></i>
</a>


<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow-lg" style="width: 400px;">
        <div class="card-body">
            <h2 class="text-center mb-4"><i class="fas fa-sign-in-alt"></i> Connexion</h2>

            <?php if ($error_message): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-key"></i></span>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-sign-in-alt"></i> Se connecter</button>
                </div>
            </form>

            <div class="mt-3 text-center">
                <a href="register.php" class="text-decoration-none">Pas encore de compte ? S'inscrire</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
