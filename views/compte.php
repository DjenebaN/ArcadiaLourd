<?php
require_once('views/commun/header.php');

// Vérification de la connexion
if (!isset($_SESSION['utilisateur'])) {
    header('Location: index.php?page=connectioninscription');
    exit();
}

$user = $_SESSION['utilisateur'];

// Déterminer le type d'utilisateur et son badge
$type_labels = [
    1 => ['label' => 'Client', 'class' => 'bg-success'],
    2 => ['label' => 'Game Master', 'class' => 'bg-info'],
    3 => ['label' => 'Administrateur', 'class' => 'bg-danger']
];

$user_type = $type_labels[$user['type_utilisateur']] ?? ['label' => 'Utilisateur', 'class' => 'bg-secondary'];
?>

<div class="container py-5">
    <div class="row">
        <!-- Colonne de gauche : Photo de profil et informations principales -->
        <div class="col-lg-4 mb-4 mb-lg-0">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-body text-center p-4">
                    <div class="mb-4">
                        <div class="bg-light rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                            <i class="fas fa-user fa-3x text-primary"></i>
                        </div>
                        <h4 class="mb-1"><?= htmlspecialchars($user['prenom']) ?> <?= htmlspecialchars($user['nom']) ?></h4>
                        <span class="badge <?= $user_type['class'] ?> rounded-pill px-3 py-2">
                            <?= $user_type['label'] ?>
                        </span>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="index.php?page=modifierProfil" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Modifier mon profil
                        </a>
                        <?php if ($user['type_utilisateur'] == 1): ?>
                            <a href="index.php?page=mesreservations" class="btn btn-outline-primary">
                                <i class="fas fa-calendar-alt me-2"></i>Mes réservations
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Colonne de droite : Détails du compte -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <h4 class="mb-4">Informations personnelles</h4>
                    
                    <div class="row g-4">
                        <!-- Email -->
                        <div class="col-md-12">
                            <div class="d-flex align-items-center p-3" style="background: rgba(0,0,0,0.03); border-radius: 10px;">
                                <div class="flex-shrink-0 me-3">
                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="fas fa-envelope text-white"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="text-muted small">Email</div>
                                    <div class="fw-medium"><?= htmlspecialchars($user['email']) ?></div>
                                </div>
                            </div>
                        </div>

                        <!-- Téléphone -->
                        <div class="col-md-12">
                            <div class="d-flex align-items-center p-3" style="background: rgba(0,0,0,0.03); border-radius: 10px;">
                                <div class="flex-shrink-0 me-3">
                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="fas fa-phone text-white"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="text-muted small">Téléphone</div>
                                    <div class="fw-medium"><?= htmlspecialchars($user['telephone'] ?? 'Non renseigné') ?></div>
                                </div>
                            </div>
                        </div>

                        <?php if ($user['type_utilisateur'] == 1): ?>
                        <!-- Statistiques -->
                        <div class="col-12 mt-4">
                            <h5 class="mb-3">Statistiques</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="p-3 text-center" style="background: rgba(0,0,0,0.03); border-radius: 10px;">
                                        <div class="h2 mb-2 text-primary">0</div>
                                        <div class="text-muted">Réservations à venir</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 text-center" style="background: rgba(0,0,0,0.03); border-radius: 10px;">
                                        <div class="h2 mb-2 text-primary">0</div>
                                        <div class="text-muted">Salles visitées</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

