<?php
include('controllers/affichage/afficherReservation.php');
?>

<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="mb-0">Mes Réservations</h1>
                <a href="index.php?page=reservation" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-2"></i>Nouvelle réservation
                </a>
            </div>

            <?php if (empty($sallereserver)): ?>
                <div class="text-center py-5" style="background: rgba(0,0,0,0.03); border-radius: 10px;">
                    <i class="fas fa-calendar-alt fa-4x mb-3 text-muted"></i>
                    <h3 class="text-muted">Aucune réservation</h3>
                    <p class="text-muted mb-4">Vous n'avez pas encore de réservations actives.</p>
                    <a href="index.php?page=reservation" class="btn btn-primary btn-lg">
                        <i class="fas fa-plus-circle me-2"></i>Découvrir nos salles
                    </a>
                </div>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($sallereserver as $reservation): 
                        $now = new DateTime();
                        $reservation_date = new DateTime($reservation['heure_debut']);
                        $is_future = $reservation_date > $now;
                        $status_class = $is_future ? 'border-primary' : 'border-secondary';
                        $status_bg = $is_future ? 'bg-primary' : 'bg-secondary';
                    ?>
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm border-2 <?= $status_class ?>" style="border-radius: 15px;">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h4 class="card-title mb-0"><?= htmlspecialchars($reservation['nom']) ?></h4>
                                    <span class="badge <?= $status_bg ?> rounded-pill">
                                        <?= $is_future ? 'À venir' : 'Passée' ?>
                                    </span>
                                </div>
                                
                                <div class="mb-4">
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

                                <?php if ($is_future): ?>
                                    <button class="btn btn-outline-danger w-100" 
                                            onclick="if(confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')) 
                                                    window.location.href='index.php?page=contact'">
                                        <i class="fas fa-times me-2"></i>Annuler la réservation
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
