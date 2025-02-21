<?php
//afficher reservation des utilisateurs
include('controllers/affichage/afficherReservation.php');

// Vérifier si l'utilisateur est connecté et est un gamemaster
if (!isset($_SESSION['utilisateur']) || $_SESSION['utilisateur']['type_utilisateur'] != 2) {
    header('Location: index.php?page=accueil');
    exit();
}
?>

<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="mb-0">Tableau de bord Game Master</h1>
                <div class="badge bg-info text-white p-2">
                    <i class="fas fa-user-shield me-2"></i>Game Master
                </div>
            </div>
        </div>
    </div>

    <!-- Réservations à assigner -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
        <div class="card-header bg-white pt-4 pb-3 border-0">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="fas fa-clipboard-list text-primary me-2"></i>
                    Réservations à assigner
                </h4>
                <span class="badge bg-primary rounded-pill">
                    <?= count($allreservations) ?> en attente
                </span>
            </div>
        </div>
        <div class="card-body p-4">
            <?php if (empty($allreservations)): ?>
                <div class="text-center py-5" style="background: rgba(0,0,0,0.03); border-radius: 10px;">
                    <i class="fas fa-check-circle fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Aucune réservation en attente</h5>
                    <p class="text-muted mb-0">Toutes les réservations ont été assignées</p>
                </div>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($allreservations as $reservation): ?>
                        <div class="col-md-6">
                            <div class="card h-100 border" style="border-radius: 10px;">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="card-title mb-0"><?= htmlspecialchars($reservation['nom']) ?></h5>
                                        <span class="badge bg-warning text-dark">
                                            <i class="fas fa-clock me-1"></i>En attente
                                        </span>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-user me-2 text-primary"></i>
                                            <span><?= htmlspecialchars($reservation['utilisateur_nom']) . ' ' . htmlspecialchars($reservation['utilisateur_prenom']) ?></span>
                                        </div>
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-calendar me-2 text-primary"></i>
                                            <span><?= date('d/m/Y', strtotime($reservation['heure_debut'])) ?></span>
                                        </div>
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-clock me-2 text-primary"></i>
                                            <span><?= date('H:i', strtotime($reservation['heure_debut'])) ?> - <?= date('H:i', strtotime($reservation['heure_fin'])) ?></span>
                                        </div>
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-users me-2 text-primary"></i>
                                            <span><?= htmlspecialchars($reservation['nb_participants']) ?> participants</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-euro-sign me-2 text-primary"></i>
                                            <span><?= htmlspecialchars($reservation['prix_total']) ?> €</span>
                                        </div>
                                    </div>

                                    <form action="controllers/gameMasterController.php" method="post">
                                        <input type="hidden" name="action" value="assignationGamemaster">
                                        <input type="hidden" name="reservation_id" value="<?= $reservation['id'] ?>">
                                        <input type="hidden" name="game_master_id" value="<?= $_SESSION['utilisateur']['id'] ?>">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fas fa-user-check me-2"></i>S'assigner cette réservation
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Réservations assignées -->
    <div class="card border-0 shadow-sm" style="border-radius: 15px;">
        <div class="card-header bg-white pt-4 pb-3 border-0">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="fas fa-calendar-check text-success me-2"></i>
                    Mes réservations assignées
                </h4>
                <span class="badge bg-success rounded-pill">
                    <?= count($reservationsAssignees) ?> assignée(s)
                </span>
            </div>
        </div>
        <div class="card-body p-4">
            <?php if (empty($reservationsAssignees)): ?>
                <div class="text-center py-5" style="background: rgba(0,0,0,0.03); border-radius: 10px;">
                    <i class="fas fa-calendar-alt fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Aucune réservation assignée</h5>
                    <p class="text-muted mb-0">Assignez-vous des réservations pour les voir apparaître ici</p>
                </div>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($reservationsAssignees as $reservation): ?>
                        <div class="col-md-6">
                            <div class="card h-100 border-success border-2" style="border-radius: 10px;">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="card-title mb-0"><?= htmlspecialchars($reservation['salle_nom']) ?></h5>
                                        <span class="badge bg-success">
                                            <i class="fas fa-check me-1"></i>Assigné
                                        </span>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-user me-2 text-success"></i>
                                            <span><?= htmlspecialchars($reservation['utilisateur_nom']) . ' ' . htmlspecialchars($reservation['utilisateur_prenom']) ?></span>
                                        </div>
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-calendar me-2 text-success"></i>
                                            <span><?= date('d/m/Y', strtotime($reservation['heure_debut'])) ?></span>
                                        </div>
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-clock me-2 text-success"></i>
                                            <span><?= date('H:i', strtotime($reservation['heure_debut'])) ?> - <?= date('H:i', strtotime($reservation['heure_fin'])) ?></span>
                                        </div>
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-users me-2 text-success"></i>
                                            <span><?= htmlspecialchars($reservation['nb_participants']) ?> participants</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-euro-sign me-2 text-success"></i>
                                            <span><?= htmlspecialchars($reservation['prix_total']) ?> €</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
