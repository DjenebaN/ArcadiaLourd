<?php
session_start();
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escape Arcadia</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- Google Fonts - Raleway -->
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
    
<nav class="navbar navbar-expand-lg fixed-top">
  <div class="container">
    <a class="navbar-brand" href="/projetfin/"><i class="fas fa-dungeon me-2"></i>Escape Arcadia</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <?php if (!isset($_SESSION['utilisateur']) || ($_SESSION['utilisateur']['type_utilisateur'] != 3 && $_SESSION['utilisateur']['type_utilisateur'] != 2)) : ?>
        <li class="nav-item">
          <a class="nav-link <?php echo !isset($_GET['page']) || $_GET['page'] === 'accueil' ? 'active' : ''; ?>" href="/projetfin/"><i class="fas fa-home me-1"></i>Accueil</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo isset($_GET['page']) && $_GET['page'] === 'reservation' ? 'active' : ''; ?>" href="index.php?page=reservation"><i class="fas fa-book me-1"></i>Réservation</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo isset($_GET['page']) && $_GET['page'] === 'faq' ? 'active' : ''; ?>" href="index.php?page=faq"><i class="fas fa-question-circle me-1"></i>FAQ</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo isset($_GET['page']) && $_GET['page'] === 'contact' ? 'active' : ''; ?>" href="index.php?page=contact"><i class="fas fa-envelope me-1"></i>Contact</a>
        </li>
        <?php endif; ?>
        <?php if (isset($_SESSION['utilisateur']) && $_SESSION['utilisateur']['type_utilisateur'] == 3) : ?>
        <li class="nav-item">
          <a class="nav-link <?php echo isset($_GET['page']) && $_GET['page'] === 'gestionSalles' ? 'active' : ''; ?>" href="index.php?page=gestionSalles"><i class="fas fa-door-closed me-1"></i>Gestion des Salles</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo isset($_GET['page']) && $_GET['page'] === 'gestionReservations' ? 'active' : ''; ?>" href="index.php?page=gestionReservations"><i class="fas fa-calendar-check me-1"></i>Gestion des Réservations</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo isset($_GET['page']) && $_GET['page'] === 'gestionGamemasters' ? 'active' : ''; ?>" href="index.php?page=gestionGamemasters"><i class="fas fa-user-tie me-1"></i>Gestion des GM</a>
        </li>
        <?php endif; ?>
        <?php if (isset($_SESSION['utilisateur']) && $_SESSION['utilisateur']['type_utilisateur'] == 2) : ?>
        <li class="nav-item">
          <a class="nav-link <?php echo isset($_GET['page']) && $_GET['page'] === 'assignationGM' ? 'active' : ''; ?>" href="index.php?page=assignationGM"><i class="fas fa-tasks me-1"></i>Mes Assignations</a>
        </li>
        <?php endif; ?>
      </ul>
      <ul class="navbar-nav">
        <?php if (isset($_SESSION['utilisateur'])) : ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="fas fa-user-circle me-1"></i><?php echo htmlspecialchars($_SESSION['utilisateur']['prenom']); ?>
            </a>
            <?php if (($_SESSION['utilisateur']['type_utilisateur'])==3) : ?>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="index.php?page=compte"><i class="fas fa-user me-2"></i>Mon Profil</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="index.php?page=deconnexion"><i class="fas fa-sign-out-alt me-2"></i>Déconnexion</a></li>
              </ul>
            <?php elseif (($_SESSION['utilisateur']['type_utilisateur'])==2) : ?>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="index.php?page=compte"><i class="fas fa-user me-2"></i>Mon Profil</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="index.php?page=deconnexion"><i class="fas fa-sign-out-alt me-2"></i>Déconnexion</a></li>
              </ul>
            <?php else : ?>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="index.php?page=compte"><i class="fas fa-user me-2"></i>Mon Profil</a></li>
                <li><a class="dropdown-item" href="index.php?page=mesreservations"><i class="fas fa-ticket-alt me-2"></i>Mes Réservations</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="index.php?page=deconnexion"><i class="fas fa-sign-out-alt me-2"></i>Déconnexion</a></li>
              </ul>
            <?php endif; ?>
          </li>
        <?php else : ?>
          <li class="nav-item">
            <a class="nav-link" href="index.php?page=connectioninscription">Connection / Inscription</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>