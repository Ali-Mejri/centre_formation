<?php
// Inclure le fichier de connexion à la base de données
include('db.php');
session_start();

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données du formulaire
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $user_type = $_POST['user_type'] ?? 'etudiant'; // Valeur par défaut : étudiant
    $date_naissance = $_POST['date_naissance'] ?? '';
    $telephone = $_POST['telephone'] ?? '';
    $adresse = $_POST['adresse'] ?? '';

    if (!empty($nom) && !empty($prenom) && !empty($email) && !empty($password) && !empty($confirm_password)) {
        if ($password === $confirm_password) {
            try {
                // Vérifier si l'email est déjà utilisé
                $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email");
                $stmt->bindParam(':email', $email);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    $error_message = "Cet email est déjà utilisé.";
                } else {
                    // Créer un mot de passe crypté
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    // Insérer l'utilisateur dans la base de données
                    $stmt = $conn->prepare("INSERT INTO users (nom, prenom, email, password, user_type, date_naissance, telephone, adresse) 
                                            VALUES (:nom, :prenom, :email, :password, :user_type, :date_naissance, :telephone, :adresse)");
                    $stmt->bindParam(':nom', $nom);
                    $stmt->bindParam(':prenom', $prenom);
                    $stmt->bindParam(':email', $email);
                    $stmt->bindParam(':password', $hashed_password);
                    $stmt->bindParam(':user_type', $user_type);
                    $stmt->bindParam(':date_naissance', $date_naissance);
                    $stmt->bindParam(':telephone', $telephone);
                    $stmt->bindParam(':adresse', $adresse);

                    $stmt->execute();

                    // Message de succès
                    $success_message = "Inscription réussie. Vous pouvez maintenant vous connecter.";
                }
            } catch (PDOException $e) {
                $error_message = "Erreur : " . $e->getMessage();
            }
        } else {
            $error_message = "Les mots de passe ne correspondent pas.";
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
    <title>Inscription</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow-lg" style="width: 400px;">
        <div class="card-body">
            <h2 class="text-center mb-4"><i class="fas fa-user-plus"></i> Inscription</h2>

            <?php if ($error_message): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
            <?php endif; ?>
            
            <?php if ($success_message): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success_message) ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" id="nom" name="nom" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="prenom" class="form-label">Prénom</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" id="prenom" name="prenom" class="form-control" required>
                    </div>
                </div>

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
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" id="password" name="password" class="form-control" required>
                        <span class="input-group-text" id="togglePassword" style="cursor: pointer;"><i class="fas fa-eye"></i></span>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirmer le mot de passe</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                        <span class="input-group-text" id="toggleConfirmPassword" style="cursor: pointer;"><i class="fas fa-eye"></i></span>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="user_type" class="form-label">Rôle</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                        <select id="user_type" name="user_type" class="form-select">
                            <option value="etudiant">Étudiant</option>
                            <option value="formateur">Formateur</option>
                            <option value="admin">Administrateur</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="date_naissance" class="form-label">Date de naissance</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                        <input type="date" id="date_naissance" name="date_naissance" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="telephone" class="form-label">Numéro de téléphone</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                        <input type="text" id="telephone" name="telephone" class="form-control">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="adresse" class="form-label">Adresse</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                        <textarea id="adresse" name="adresse" class="form-control"></textarea>
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-user-plus"></i> S'inscrire</button>
                </div>
            </form>

            <div class="mt-3 text-center">
                <a href="login.php" class="text-decoration-none">Déjà un compte ? Se connecter</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function () {
        const passwordField = document.getElementById('password');
        const type = passwordField.type === 'password' ? 'text' : 'password';
        passwordField.type = type;
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });

    document.getElementById('toggleConfirmPassword').addEventListener('click', function () {
        const confirmPasswordField = document.getElementById('confirm_password');
        const type = confirmPasswordField.type === 'password' ? 'text' : 'password';
        confirmPasswordField.type = type;
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });
</script>
</body>
</html>
