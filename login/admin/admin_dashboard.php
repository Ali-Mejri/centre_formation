<?php
session_start();

// Vérification si l'utilisateur est connecté et est un admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        iframe {
            margin-left: -12%;
            width: 130%;
            height: 100vh;
            border: none;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-info fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Admin <?= htmlspecialchars($_SESSION['user_name']) ?></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="offcanvas offcanvas-end" tabindex="-8" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menu Admin</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
          <!-- Option de se déconnecter -->
          <li class="nav-item">
            <a class="nav-link" href="#" onclick="window.location.href='../logout.php'; return false;">Se déconnecter</a>
          </li>
          <!-- Autres options du menu -->
          <li class="nav-item">
            <a class="nav-link active" href="#page1" onclick="changeIframe('modifier.php')">Modifier un utilisateur</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#page2" onclick="changeIframe('supprimer.php')">Supprimer un utilisateur</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#page3" onclick="changeIframe('ajouter.php')">Ajouter un utilisateur</a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</nav>

<!-- Conteneur principal avec iframe qui occupe toute la page -->
<div class="container-fluid mt-5">
    <iframe id="adminIframe" src="modifier.php"></iframe>
</div>

<script>
// Fonction pour changer la source de l'iframe selon le menu sélectionné
function changeIframe(page) {
    document.getElementById('adminIframe').src = page;
}
</script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
