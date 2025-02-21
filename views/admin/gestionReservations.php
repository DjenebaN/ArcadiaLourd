<?php
include('controllers/affichage/afficherReservation.php');

if (!isset($_SESSION['utilisateur']) || $_SESSION['utilisateur']['type_utilisateur'] != 3) {
    header('Location: index.php?page=accueil');
    exit();
}
?>

<div class="container-fluid py-5">
    <div class="row mb-4">
        <div class="col-12">
            <!-- En-tête -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="display-4 mb-1">Gestion des Réservations</h1>
                    <p class="text-muted">Consultez et gérez toutes les réservations</p>
                </div>
                <div class="badge bg-primary p-3">
                    <i class="fas fa-calendar-alt fa-2x"></i>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card border-0 bg-primary text-white shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-white-50 mb-2">Total Réservations</h6>
                                    <h2 class="mb-0"><?= count($allreservations) ?></h2>
                                </div>
                                <div class="bg-white bg-opacity-25 p-3 rounded">
                                    <i class="fas fa-calendar-check fa-2x text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 bg-success text-white shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-white-50 mb-2">Chiffre d'Affaires</h6>
                                    <h2 class="mb-0"><?= array_sum(array_column($allreservations, 'prix_total')) ?>€</h2>
                                </div>
                                <div class="bg-white bg-opacity-25 p-3 rounded">
                                    <i class="fas fa-euro-sign fa-2x text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 bg-info text-white shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-white-50 mb-2">Total Participants</h6>
                                    <h2 class="mb-0"><?= array_sum(array_column($allreservations, 'nb_participants')) ?></h2>
                                </div>
                                <div class="bg-white bg-opacity-25 p-3 rounded">
                                    <i class="fas fa-users fa-2x text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 bg-warning text-white shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-white-50 mb-2">Réservations du Jour</h6>
                                    <h2 class="mb-0"><?= count(array_filter($allreservations, function($r) { return date('Y-m-d', strtotime($r['heure_debut'])) === date('Y-m-d'); })) ?></h2>
                                </div>
                                <div class="bg-white bg-opacity-25 p-3 rounded">
                                    <i class="fas fa-clock fa-2x text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Carte principale -->
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-header bg-white pt-4 pb-3 border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <?php if (isset($_GET['success']) && $_GET['success'] == 'ReservationAnnuler'): ?>
                                <div class="alert alert-success d-flex align-items-center mb-0" role="alert">
                                    <i class="fas fa-check-circle me-2"></i>
                                    <div>La réservation a été annulée avec succès.</div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <?php if (empty($allreservations)): ?>
                        <div class="text-center py-5" style="background: rgba(0,0,0,0.03); border-radius: 10px;">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucune réservation à venir</h5>
                            <p class="text-muted mb-0">Les nouvelles réservations apparaîtront ici</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th class="ps-4">Client</th>
                                        <th>Salle</th>
                                        <th>Date & Heure</th>
                                        <th>Participants</th>
                                        <th>Prix</th>
                                        <th>Statut</th>
                                        <th class="text-end pe-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($allreservations as $reservation): ?>
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-light rounded-circle p-2 me-3">
                                                        <i class="fas fa-user text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0"><?= htmlspecialchars($reservation['utilisateur_prenom']) ?> <?= htmlspecialchars($reservation['utilisateur_nom']) ?></h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-door-closed text-primary me-2"></i>
                                                    <?= htmlspecialchars($reservation['nom']) ?>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-calendar-alt text-primary me-2"></i>
                                                    <div>
                                                        <div><?= date('d/m/Y', strtotime($reservation['heure_debut'])) ?></div>
                                                        <small class="text-muted">
                                                            <?= date('H:i', strtotime($reservation['heure_debut'])) ?> - 
                                                            <?= date('H:i', strtotime($reservation['heure_fin'])) ?>
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-users text-primary me-2"></i>
                                                    <?= htmlspecialchars($reservation['nb_participants']) ?> personnes
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-euro-sign text-primary me-2"></i>
                                                    <?= htmlspecialchars($reservation['prix_total']) ?> €
                                                </div>
                                            </td>
                                            <td>
                                                <?php
                                                $now = new DateTime();
                                                $reservationDate = new DateTime($reservation['heure_debut']);
                                                if ($reservationDate < $now): ?>
                                                    <span class="badge bg-success rounded-pill px-3 py-2">
                                                        <i class="fas fa-check me-1"></i>Terminée
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-primary rounded-pill px-3 py-2">
                                                        <i class="fas fa-clock me-1"></i><?php echo htmlspecialchars($reservation['status_nom']) ?>
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-end pe-4">
                                                <form action="controllers/reservationsController.php" method="post" class="d-inline">
                                                    <input type="hidden" name="action" value="annuler">
                                                    <input type="hidden" name="id" value="<?= $reservation['id'] ?>">
                                                    <button type="submit" class="btn btn-outline-danger btn-sm px-3" 
                                                            onclick="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')">
                                                        <i class="fas fa-times me-2"></i>Annuler
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
