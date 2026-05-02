<?php
/**
 * views/layout/header.php
 * En-tête commun à toutes les pages publiques
 * Variable attendue : $pageCourante (ex: 'accueil', 'evenements', 'reunions')
 */

$user       = $_SESSION['user'] ?? null;
$pageCourante = $pageCourante ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Club Joker — <?= htmlspecialchars($titreePage ?? 'Bienvenue') ?></title>
  <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🃏</text></svg>">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link rel="stylesheet" href="public/style.css">
</head>
<body>

<!-- ═══════ NAVBAR ═══════ -->
<nav class="navbar navbar-joker navbar-expand-lg">
  <div class="container">
    <a class="navbar-brand" href="index.php?page=accueil">
      🃏 Club <span style="color:var(--red-light)">Joker</span>
      <span class="brand-dot"></span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#mainNav" aria-label="Menu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav me-auto ms-4">
        <li class="nav-item">
          <a class="nav-link <?= $pageCourante === 'accueil' ? 'active' : '' ?>"
             href="index.php?page=accueil">Accueil</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= $pageCourante === 'evenements' ? 'active' : '' ?>"
             href="index.php?page=evenements">Événements</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= $pageCourante === 'reunions' ? 'active' : '' ?>"
             href="index.php?page=reunions">Réunions</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="index.php?page=accueil#a-propos">À propos</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="index.php?page=accueil#rejoindre">Nous rejoindre</a>
        </li>
      </ul>

      <!-- Auth Links -->
      <div class="d-flex gap-2 mt-2 mt-lg-0">
        <?php if ($user): ?>
          <?php if ($user['role'] === 'admin'): ?>
            <a href="index.php?page=admin_dashboard" class="btn btn-joker-red btn-sm">
              <i class="bi bi-speedometer2 me-1"></i>Dashboard
            </a>
          <?php else: ?>
            <a href="index.php?page=membre_dashboard" class="btn btn-joker-blue btn-sm">
              <i class="bi bi-grid me-1"></i>Dashboard
            </a>
          <?php endif; ?>
          <a href="index.php?page=logout" class="btn btn-joker-outline btn-sm">
            <i class="bi bi-box-arrow-left me-1"></i>Déconnexion
          </a>
        <?php else: ?>
          <a href="index.php?page=login" class="btn btn-joker-blue btn-sm">Connexion</a>
          <a href="index.php?page=accueil#rejoindre" class="btn btn-joker-red btn-sm">Nous rejoindre</a>
        <?php endif; ?>
      </div>

    </div>
  </div>
</nav>